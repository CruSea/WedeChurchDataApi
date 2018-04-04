<?php

namespace App\Http\Middleware;
use Closure;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;
use App\Http\Controllers\JWTAuthenticateController;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
class AuthenticatedMiddleware
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
            $token = JWTAuth::getToken();
            if(! $token){
                return response()->json('token_not_provided');
            }
            try{
                    $user = JWTAuth::toUser($token);
                    if($user){
                        return $next($request);
                    }

                    return response()->json("user_not_found");
                }
                catch (TokenInvalidException $e) {
                    return response()->json("token_invalid");
                }
                catch (TokenExpiredException $e) {
                    return response()->json("token_expired");
                }
            }
    }

