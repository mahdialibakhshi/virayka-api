<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Province;
use App\Models\OrderStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\confirmRoleSms;
use App\Notifications\DenyRoleSms;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //return Users Ajax
    public function AjaxGet()
    {

        $users = User::where('role', '!=', 1)->get();
        return response()->json($users);
    }

    public function searchUser()
    {
        $name = $_POST['name'];
        $users = User::where('name', 'LIKE', '%' . $name . '%')
            ->orwhere('cellphone', 'LIKE', '%' . $name . '%')
            ->get();
        return response()->json($users);
    }

    public function index()
    {
        $users = User::latest()->paginate(20);
        $show_per_page = 1;
        $has_request_change_role = User::where('role_request_status', 1)->exists();
        $roles = Role::where('is_show',1)->get();
        return view('admin.users.index', compact('users', 'has_request_change_role', 'show_per_page', 'roles'));
    }

    public function index_pagination($show_per_page)
    {
        if ($show_per_page === 'all') {
            $users_count = User::latest()->count();
            $users = User::latest()->paginate($users_count);
        } elseif ($show_per_page == 'default') {
            $users = User::latest()->paginate(20);
            $show_per_page = null;
        } else {
            $users = User::latest()->paginate($show_per_page);
        }
        $roles = Role::where('is_show',1)->get();
        $has_request_change_role = User::where('role_request_status', 1)->exists();
        return view('admin.users.index', compact('users', 'has_request_change_role', 'show_per_page', 'roles'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'is_active' => 'required',
            'role' => 'required',
            'cellphone' => 'required|iran_mobile|unique:users,cellphone',
            'email' => 'nullable|email|unique:users,email',
            'tel' => 'nullable',
            'avatar' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
        ]);
        try {
            DB::beginTransaction();
            if ($request->has('avatar')) {
                $fileNameImage = generateFileName($request->avatar->getClientOriginalName());
                $request->avatar->move(public_path(env('USER_IMAGES_UPLOAD_PATH')), $fileNameImage);
            }
            User::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
                'role' => $request->role,
                'cellphone' => $request->cellphone,
                'email' => $request->email,
                'tel' => $request->tel,
                'avatar' => $request->avatar,
            ]);
            DB::commit();
            alert()->success('کاربر مورد نظر با موفقیت ایجاد شد', 'با تشکر')->persistent('ok');
            return redirect()->route('admin.user.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            alert()->error($exception->getMessage(), 'ERROR')->persistent('ok');
            return redirect()->back();
        }
    }

    public function edit(User $user)
    {
        $user_roles = Role::where('is_show',0)->get();
        $roles = Role::where('is_show',1)->get();
        $provinces = Province::all();
        $permissions = Permission::all();
        return view('admin.users.edit', compact('user', 'roles', 'provinces', 'permissions','user_roles'));
    }

    public function userTickets(User $user)
    {
        $tickets = Ticket::where('user_id', $user->id)->latest()->paginate(20);
        return view('admin.users.tickets', compact('tickets'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'cellphone' => 'required|iran_mobile|unique:users,cellphone,' . $user->id,
            'tel' => 'nullable',
            'national_code' => 'nullable',
            'avatar' => 'nullable|max:10000|mimes:png,jpg,jpeg,gif',
        ]);
        $avatar = $user->avatar;
        if ($request->has('avatar')) {
            $avatar = 'avatar' . time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path(env('USER_IMAGES_UPLOAD_PATH')), $avatar);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->user_role,
            'cellphone' => $request->cellphone,
            'national_code' => $request->national_code,
            'tel' => $request->tel,
            'avatar' => $avatar,
            'is_active' => $request->is_active,
        ]);
        $user->syncRoles($request->role);
        $permissions=$request->except(['name','cellphone','tel','is_active','national_code','_token','_method','close','role','user_role','email']);
        $user->syncPermissions($permissions);
        alert()->success('اطلاعات کاربر با موفقیت ویرایش شد', 'با تشکر');
        if ($request->close == 0) {
            return redirect()->back();
        }
        if ($request->close == 1) {
            return redirect()->route('admin.user.index');
        }
    }

    public function destroy(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::findOrFail($user_id);
        if ($user->getRawOriginal('role') == 1) {
            $msg = 'امکان حذف ادمین وجود ندارد';
            return response()->json([0, $msg]);
        }
        $path = public_path(env('USER_IMAGES_UPLOAD_PATH') . $user->avatar);
        if (file_exists($path) and !is_dir($path)) {
            unlink($path);
        }
        $msg = 'کاربر مورد نظر با موفقیت حذف شد';
        $user->delete();
        return response()->json([1, $msg]);

    }

    public function change_role_index()
    {
        $users = User::where('role_request_status', 1)->get();
        return view('admin.users.change_role.index', compact('users'));
    }

    public function change_role_edit(User $user)
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('admin.users.change_role.edit', compact('user', 'roles'));
    }

    public function change_role_confirm(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_id = $request->user_id;
            $role = $request->role;
            $user = User::where('id', $user_id)->first();
            $user->update([
                'role' => $role,
                'role_request_status' => 0,
            ]);
            DB::commit();
            try {
                $msg = 'تغییر سطح کاربری با موفقیت انجام شد';
                $user->notify(new confirmRoleSms());
                return response()->json([1, $msg]);
            } catch (\Exception $exception) {
                $msg = 'تغییرات با موفقیت انجام شد اما ارسال پیامک به مشکل خورده است.با پشتیبان سایت خود تماس بگیرید';
                return response()->json([1, $msg]);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }
    }

    public function change_role_deny(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_id = $request->user_id;
            $user = User::where('id', $user_id)->first();
            $user->update([
                'role_request_status' => 2,
            ]);
            $user->notify(new DenyRoleSms());
            DB::commit();
            return response()->json([1]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }
    }

    public function get(Request $request)
    {
        $role_id = $request->role_id;
        try {
            DB::beginTransaction();
            $name = $request->name;

            if ($role_id != 0) {
                $users = User::where('role', $role_id)->where(function ($query) use ($name) {
                    $query->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('cellphone', 'LIKE', '%' . $name . '%');
                })->get();
            } else {
                $users = User::where('name', 'LIKE', '%' . $name . '%')->orWhere('cellphone', 'LIKE', '%' . $name . '%')->get();
            }
            $html = '';
            foreach ($users as $item) {
                if ($item->is_active == 0) {
                    $active = 'bg-danger text-white';
                    $text_white = 'text-white';
                    $text = 'غیر فعال';
                } else {
                    $active = '';
                    $text_white = '';
                    $text = 'فعال';
                }
                if (isset($item->Role->display_name)) {
                    $display_name = $item->Role->display_name;
                } else {
                    $display_name = '-';
                }
                $html = $html . '<tr class="' . $active . '">
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            <a class="' . $text_white . '" href="' . route('admin.user.edit', ['user' => $item->id]) . '">
                                            ' . $item->name . '
                                            </a>
                                        </td>
                                        <td>
                                            ' . $item->cellphone . '
                                        </td>
                                        <td>
                                            ' . $display_name . '
                                        </td>
                                        <td>
                                            ' . $text . '
                                        </td>
                                        <td>
                                            <a href="' . route('admin.user.edit', ['user' => $item->id]) . '"
                                               class="btn btn-info btn-sm">
                                                <i class="fa fa-user"></i>
                                            </a>
                                            <button type="button" onclick="removeModal(' . $item->id . ')"
                                                    class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>';
            }
            DB::commit();
            return response()->json([1, $html]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }

    }

    public function order($user)
    {
        $total_sale = 0;
        $order_status = OrderStatus::all();
        $orders = Order::where('user_id', $user)->where('status', '!=', 0)->latest()->paginate(100);
        $show_per_page = 1;
        $total_orders = Order::where('user_id', $user)->where('status', '!=', 0)->get();
        $setting = Setting::first();
        foreach ($total_orders as $order) {
            $total_sale = $total_sale + $order->total_amount;
        }
        return view('admin.users.order', compact('orders',
            'total_sale',
            'show_per_page', 'order_status', 'setting'));
    }

}
