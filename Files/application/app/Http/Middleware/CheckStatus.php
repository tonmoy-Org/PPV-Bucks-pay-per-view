<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = auth()->user();
            if ($user->status == 1 && $user->ev && $user->sv && $user->tv) {
                return $next($request);
            } else {
                if ($user->status == 0) {
                    Auth::logout();
                    $notify[] = ['error', 'Your account is currently PENDING admin approval. You will get access once approved by the admin.'];
                    return to_route('user.login')->withNotify($notify);
                }

                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark'=>'unverified',
                        'status'=>'error',
                        'message'=>['error'=>$notify],
                        'data'=>[
                            'is_ban'=>$user->status,
                            'email_verified'=>$user->ev,
                            'mobile_verified'=>$user->sv,
                            'twofa_verified'=>$user->tv,
                        ],
                    ]);
                }else{
                    return to_route('user.authorization');
                }
            }
        }
        abort(403);
    }
}
