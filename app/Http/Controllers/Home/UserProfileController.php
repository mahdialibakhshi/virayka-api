<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InformMe;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Notifications\newTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentMethods;

class UserProfileController extends Controller
{

    public function index()
    {
        $user = User::find(auth()->id());
        return view('home.users_profile.profile', compact('user'));
    }

    public function userUpdateInfo(Request $request)
    {

        $user = User::find(auth()->id());
        $request->validate([
            'avatar' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            'name' => 'required|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'national_code' => 'required|melli_code|unique:users,national_code,' . $user->id,
            'jensiyat' => 'required',
        ]);

        if ($request->has('avatar')) {
            $fileNameImage = generateFileName($request->avatar->getClientOriginalName());
            $request->avatar->move(public_path(env('USER_IMAGES_UPLOAD_PATH')), $fileNameImage);
            $path = public_path(env('USER_IMAGES_UPLOAD_PATH') . $request->avatar);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
        }
        if ($request->jensiyat == 1) {
            $random = rand(1, 3);
            $random_avatar = $random . '.png';

        } else {
            $random = rand(4, 6);
            $random_avatar = $random . '.png';
        }
        $user->update([
            'avatar' => $request->has('avatar') ? $fileNameImage : $random_avatar,
            'name' => $request->name,
            'email' => $request->email,
            'national_code' => $request->national_code,
            'jensiyat' => $request->jensiyat,
        ]);
        alert()->success('اطلاعات شما با موفقیت ویرایش شد', 'باتشکر');
        return redirect()->back();
    }
    //tickets
    public function TicketIndex()
    {
        $user = auth()->user();
        $tickets = Ticket::where('user_id', $user->id)
            ->where('parent', 0)
            ->latest()
            ->paginate(10);
        return view('home.users_profile.ticket.index', compact('user', 'tickets'));
    }

    public function createTicket()
    {
        $user = auth()->user();
        return view('home.users_profile.ticket.create', compact('user'));
    }

    public function storeTicket(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'title' => 'required|string:max:50',
            'description' => 'required|string|max:1000',
            'file' => 'nullable|max:10000|mimes:png,jpg,jpeg,gif,pdf,doc,docx',
        ]);
        $fileName = null;
        if ($request->has('file')) {
            $fileName = 'ticket_' . time() . '.' . $request->file->extension();
            $request->file->move(public_path(env('UPLOAD_FILE_Ticket')), $fileName);
        }
        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'file' => $fileName,
            'user_id' => $user->id,
        ]);
        $admin = User::where('role', 1)->first();
        $admin['ticket_sender'] = $user->name;
        $admin->notify(new newTicket());

        alert()->success('تیکت جدید با موفقیت ثبت شد', 'با تشکر');
        return redirect()->route('home.ticket.index');

    }

    public function showTicket(Ticket $ticket)
    {
        $user = User::first();
        $conversation = Ticket::where('parent', $ticket->id)->get();
        return view('home.users_profile.ticket.show', compact('user', 'ticket', 'conversation'));
    }

    public function replay(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        $request->validate([
            'description' => 'required|max:10000',
            'file' => 'nullable|max:10000|mimes:png,jpg,jpeg,gif,pdf,doc,docx',
        ]);
        $fileName = null;
        if ($request->has('file')) {
            $fileName = 'ticket_' . time() . '.' . $request->file->extension();
            $request->file->move(public_path(env('UPLOAD_FILE_Ticket')), $fileName);
        }
        Ticket::create([
            'user_id' => $ticket->user_id,
            'title' => $ticket->title,
            'file' => $fileName,
            'description' => $request->description,
            'parent' => $ticket->id,
        ]);
        $ticket->update([
            'status_id' => 4
        ]);

        $user = auth()->user();
        $admin = User::where('role', 1)->first();
        $admin['ticket_sender'] = $user->name;
        $admin->notify(new newTicket());
        alert()->success('پاسخ با موفقیت ارسال شد', 'با تشکر');
        return redirect()->back();
    }

    public function wallet()
    {
        $user = User::where('id', auth()->id())->first();
        $wallet = Wallet::where('user_id', $user->id)->exists();
        if ($wallet == false) {
            Wallet::create([
                'user_id' => $user->id,
            ]);
        }
        $wallet = Wallet::where('user_id', $user->id)->first();
        //wallet history
        $wallet_history = WalletHistory::where('user_id', $user->id)->orderby('id', 'desc')->paginate(20);
        $PaymentMethods=PaymentMethods::where('is_active',1)->where('name','!=','cash')->get();
        return view('home.users_profile.wallet', compact('wallet',
            'wallet_history',
            'user','PaymentMethods'));
    }

    public function orders()
    {
        $user = User::find(auth()->id());
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        $order_status=OrderStatus::all();
        $setting=Setting::first();
        return view('home.users_profile.orders', compact(
            'orders'
            , 'user',
            'order_status',
            'setting'
        ));
    }

    public function informMe()
    {
        $user = User::where('id', auth()->id())->first();
        //wallet history
        $products = InformMe::where('user_id', $user->id)->paginate(20);
        return view('home.users_profile.informMe', compact('products', 'user'));
    }

    public function remove(Request $request)
    {
        $informMe=InformMe::where('id',$request->id)->first();
        $informMe->delete();
        return response()->json(['ok']);
    }

    public function role_request_index(){
        $user=auth()->user();
        return view('home.users_profile.role_request_index', compact('user'));
    }

    public function role_request_store(Request $request){
        $request->validate([
            'company_type'=>'required',
        ]);
        $fileNameImage_1=null;
        $fileNameImage_2=null;
        $fileNameImage_3=null;
        $fileNameImage_4=null;
        $fileNameImage_5=null;
        $fileNameImage_6=null;
        if ($request->company_type==1){
            $request->validate([
                'company_type'=>'required',
                'company_name'=>'required',
                'economic_code'=>'nullable|integer',
                'naghsh_code'=>'nullable|integer',
                'image_atach_1' => 'required|mimes:jpg,jpeg,png,svg|max:1024',
                'image_atach_2' => 'required|mimes:jpg,jpeg,png,svg|max:1024',
            ]);
            $fileNameImage_1 = generateFileName($request->image_atach_1->getClientOriginalName());
            $request->image_atach_1->move(public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH')), $fileNameImage_1);
            $path = public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH') . $request->image_atach_1);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
            $fileNameImage_2 = generateFileName($request->image_atach_2->getClientOriginalName());
            $request->image_atach_2->move(public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH')), $fileNameImage_2);
            $path = public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH') . $request->image_atach_2);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
        }

        if ($request->company_type==2){
            $request->validate([
                'company_type'=>'required',
                'company_name'=>'required',
                'economic_code'=>'required',
                'naghsh_code'=>'nullable',
                'image_atach_3' => 'required|mimes:jpg,jpeg,png,svg|max:1024',
                'image_atach_4' => 'required|mimes:jpg,jpeg,png,svg|max:1024',
                'image_atach_5' => 'required|mimes:jpg,jpeg,png,svg|max:1024',
                'image_atach_6' => 'required|mimes:jpg,jpeg,png,svg|max:1024',
            ]);
            $fileNameImage_3 = generateFileName($request->image_atach_3->getClientOriginalName());
            $request->image_atach_3->move(public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH')), $fileNameImage_3);
            $path = public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH') . $request->image_atach_3);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
            $fileNameImage_4 = generateFileName($request->image_atach_4->getClientOriginalName());
            $request->image_atach_4->move(public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH')), $fileNameImage_4);
            $path = public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH') . $request->image_atach_4);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
            $fileNameImage_5 = generateFileName($request->image_atach_5->getClientOriginalName());
            $request->image_atach_5->move(public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH')), $fileNameImage_5);
            $path = public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH') . $request->image_atach_5);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
            $fileNameImage_6 = generateFileName($request->image_atach_6->getClientOriginalName());
            $request->image_atach_6->move(public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH')), $fileNameImage_6);
            $path = public_path(env('USER_ROLE_IMAGES_UPLOAD_PATH') . $request->image_atach_6);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
        }


        $user=User::where('id',auth()->id())->first();
        $user->update([
            'image_atach_1'=>$fileNameImage_1,
            'image_atach_2'=>$fileNameImage_2,
            'image_atach_3'=>$fileNameImage_3,
            'image_atach_4'=>$fileNameImage_4,
            'image_atach_5'=>$fileNameImage_5,
            'image_atach_6'=>$fileNameImage_6,
            'company_type'=>$request->company_type,
            'company_name'=>$request->company_name,
            'economic_code'=>$request->economic_code,
            'naghsh_code'=>$request->naghsh_code,
            'role_request_status'=>1,
        ]);
        alert()->success('درخواست شما با موفقیت ثبت گردید.نتیجه درخواست از طریق پیامک به اطلاع شما خواهد رسید')->persistent('ok');
        return redirect()->back();
    }

}
