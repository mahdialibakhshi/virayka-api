<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::latest()->paginate(20);
        $show_per_page = 1;
        return view('admin.transactions.index', compact('transactions', 'show_per_page'));
    }

    public function index_pagination($show_per_page)
    {
        if ($show_per_page === 'all') {
            $transactions_count = Transaction::latest()->count();
            $transactions = Transaction::latest()->paginate($transactions_count);
        } elseif ($show_per_page == 'default') {
            $transactions = Transaction::latest()->paginate(20);
            $show_per_page = null;
        } else {
            $transactions = Transaction::latest()->paginate($show_per_page);
        }
        return view('admin.transactions.index', compact('transactions', 'show_per_page'));
    }

    public function get(Request $request)
    {
        $transaction_start = $request->transaction_start;
        $transaction_end = $request->transaction_end;
        $transaction_status = $request->transaction_status;
        $userNameSearch = $request->userNameSearch;
        //get users
        $user_ids = [];
        if ($userNameSearch == null) {
            $users = User::all();
        } else {
            $users = User::where('name', 'LIKE', '%' . $userNameSearch . '%')->get();

        }
        foreach ($users as $user) {
            array_push($user_ids, $user->id);
        }
        if ($transaction_end == null and $transaction_start == null) {
            $transactions = Transaction::where('status', $transaction_status)->whereIn('user_id', $user_ids)->get()->load('User');
        } else if ($transaction_end == null and $transaction_start != null) {
            $transactions = Transaction::where('created_at', '>', convertShamsiToGregorianDate($transaction_start))->where('status', $transaction_status)->whereIn('user_id', $user_ids)->get()->load('User');
        } else if ($transaction_end != null and $transaction_start == null) {
            $transactions = Transaction::where('created_at', '<', convertShamsiToGregorianDate($transaction_end))->where('status', $transaction_status)->whereIn('user_id', $user_ids)->get()->load('User');
        } else {
            $transactions = Transaction::where('created_at', '>', convertShamsiToGregorianDate($transaction_start))->where('created_at', '<', convertShamsiToGregorianDate($transaction_end))->where('status', $transaction_status)->whereIn('user_id', $user_ids)->get()->load('User');
        }
        $total_amount = 0;
        foreach ($transactions as $transaction) {
            $transaction['date'] = verta($transaction->created_at)->format('%d %B,Y');
            $total_amount = $total_amount + $transaction->amount;
        }
        $rows = '';
        foreach ($transactions as $transaction) {
            if ($transaction->getRawOriginal('status') == 0) {
                $class = 'text-danger';
            } else {
                $class = 'text-success';
            }
            if ($transaction->ref_id == null) {
                $ref_id = '-';
            } else {
                $ref_id = $transaction->ref_id;
            }
            $rows = $rows . '<tr class="' . $class . '">
                                    <td>-</td>
                                    <td>
                                        <a href="' . route('admin.user.edit', ['user' => $transaction->user->id]) . '">
                                        ' . $transaction->user->name . '
                                        </a>
                                    </td>
                                    <td>' . number_format($transaction->amount) . 'تومان </td>
                                    <td>' . $ref_id . '</td>
                                    <td>' . $transaction->gateway_name . '</td>
                                    <td>' . $transaction->status . '</td>
                                    <td>' . $transaction->date . '</td>
                                </tr>';
        }
        return response()->json([1, $rows, $total_amount]);
    }
}
