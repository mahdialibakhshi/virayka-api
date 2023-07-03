<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OTPSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $social_user = Socialite::driver($provider)->user();
        } catch (\Exception $ex) {
            return redirect()->route('login');
        }

        $user = User::where('email', $social_user->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name' => $social_user->getName(),
                'provider_name' => $provider,
                'avatar' => $social_user->getAvatar(),
                'email' => $social_user->getEmail(),
                'password' => Hash::make($social_user->getId()),
                'email_verified_at' => Carbon::now(),
            ]);
        }
        auth()->login($user);
        return redirect()->route('home.redirects');
    }

    public function smsLogin(Request $request)
    {
        $request->validate([
            'cellphone' => 'required|iran_mobile',
        ]);
        try {
            if ($request->has('email')) {
                $user = User::where('email', $request->email)->first();
                $otp = mt_rand(100000, 999999);
                $login_token = Hash::make('werfsfs$%^FVD0248!{DC%');
                $user->update([
                    'otp' => $otp,
                    'login_token' => $login_token,
                    'cellphone' => $request->cellphone,
                ]);
                $user->notify(new OTPSms($otp));
                return response(['login_token' => $login_token], 200);
            } else {
                $user = User::where('cellphone', $request->cellphone)->first();
                $otp = mt_rand(100000, 999999);
                $login_token = Hash::make('werfsfs$%^FVD0248!{DC%');
                if ($user) {
                    $user->update([
                        'otp' => $otp,
                        'login_token' => $login_token
                    ]);
                } else {
                    $user = User::Create([
                        'cellphone' => $request->cellphone,
                        'otp' => $otp,
                        'login_token' => $login_token
                    ]);
                }
                $user->notify(new OTPSms($otp));
                return response(['login_token' => $login_token], 200);
            }
        } catch (\Exception $ex) {
            return response(['errors' => $ex->getMessage()], 400);
        }
    }

    public function checkOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'login_token' => 'required',
        ]);
        try {
            $user = User::where('login_token', $request->login_token)->firstOrFail();
            if ($user->otp == $request->otp) {
                \auth()->login($user, $remember = true);
                return response(['ورود با موفقیت انجام شد'], 200);
            } else {
                return response(['errors' => ['otp' => ['کد تاییدیه نادرست است']]], 422);
            }


        } catch (\Exception $ex) {
            return response(['errors' => $ex->getMessage()], 400);
        }

    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'login_token' => 'required',
        ]);
        try {
            $user = User::where('login_token', $request->login_token)->firstOrFail();
            $otp = mt_rand(100000, 999999);
            $login_token = Hash::make('werfsfs$%^FVD0248!{DC%');
            $user->update([
                'otp' => $otp,
                'login_token' => $login_token
            ]);

            $user->notify(new OTPSms($otp));
            return response(['login_token' => $login_token], 200);
        } catch (\Exception $ex) {
            return response(['errors' => $ex->getMessage()], 400);
        }
    }

}
