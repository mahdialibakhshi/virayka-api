<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user == null) {
            if ($user->getRawOriginal('role')==1){
                return $next($request);
            }else{
                auth()->logout();
                return redirect()->route('login');
            }
        } else {
            auth()->logout();
            return redirect()->route('login');
        }
    }
}
