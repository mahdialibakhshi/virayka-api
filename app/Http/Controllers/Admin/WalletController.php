<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Notifications\adminChargeWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index($user_id){
        $user=User::where('id',$user_id)->first();
        $wallet=Wallet::where('user_id',$user_id)->exists();
        if ($wallet==false){
            Wallet::create([
                'user_id'=>$user_id,
            ]);
        }
        $wallet=Wallet::where('user_id',$user_id)->first();
        //wallet history
        $wallet_history=WalletHistory::where('user_id',$user_id)->orderby('id','desc')->paginate(20);
        return view('admin.wallet.index',compact('wallet','wallet_history','user'));
    }

    public function add(Request $request){

        $request->validate([
            'amount'=>'required',
        ]);
        if ($request->amount==0){
            $msg='مقدار وارد شده باید بزرگتر از صفر باشد';
            return \response()->json([0,$msg]);
        }
        try {
            $amount=$request->amount;
            $user_id=$request->user_id;
            $amount=str_replace(',','',$amount);
            //
            $wallet=Wallet::where('user_id',$user_id)->first();
            $amount_in_wallet=$wallet->amount;
            if ($request->increase_type==1){
                $new_amount=intval($amount_in_wallet)+intval($amount);
            }else{
                $new_amount=intval($amount_in_wallet)-intval($amount);
            }
            $wallet->update([
                'amount'=>$new_amount
            ]);
            //
            WalletHistory::create([
                'amount'=>$amount,
                'user_id'=>$user_id,
                'type'=>1,
                'increase_type'=>$request->increase_type,
                'previous_amount'=>$amount_in_wallet,
            ]);
          //send sms
            try {
                $new_amount=intval($amount_in_wallet)+intval($amount);
                $user=User::where('id',$user_id)->first();
                $user->notify(new adminChargeWallet($amount,$new_amount,$request->increase_type));
                $msg='تغییرات با موفقیت اعمال شد';
            }catch (\Exception $exception){
                $msg='تغییرات با موفقیت اعمال شد اما  ارسال پیامک با مشکل مواجه شد.با پشتیبان سایت تماس بگیرید';
                return \response()->json([0,$msg]);
            }
            return \response()->json([1,$msg]);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->json([0,$exception->getMessage()]);
        }

    }
}
