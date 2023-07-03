<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user=auth()->user();
        if ($user){
            if ($user->cellphone==null){
                $email=$user->email;
                return redirect()->route('login');
            }else{
                if ($user->getRawOriginal('role')!=1){
                    return $next($request);
                }else{
                    auth()->logout();
                    return redirect()->route('login');
                }
            }
        }else{
            return redirect()->route('login');
        }

    }
}
