<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DB;


class ChangePasswordController extends Controller
{
    public function process(Request $request)
    {
        self::getPasswordResetTableRow($request)->count() > 0 ? 
        self::changePassword($request): self::tokenNotFoundResponse();
    }

    private function getPasswordResetTableRow($request)
    {
        return \DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->resetToken]);
    }
    private function tokenNotFoundResponse(){
        return response()->json(['status' => false, 'message' => 'Token or Email is incorrect']);
    }
    private function changePassword($request){
        $user = User::where('email', $request->email)->first();
        // $user->update(['password'=>bcrypt($request->password)]);
        $user->password = bcrypt($request->password);
        $user->save();
        self::getPasswordResetTableRow($request)->delete();
        return response()->json(['status' => 'true', 'message' => 'Password successfully changed']);

    }
   
}
