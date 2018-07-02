<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DB;
use \Carbon\Carbon;

class PasswordResetController extends Controller
{

   public function sendEmail(Request $request){

    if(!self::validateEmail($request->email)){
        return response()->json(['status' => false, 'message' => 'Email doesnot exist']);
    }
    
    self::send($request->email);
    return response()->json(['status' => true, 'message' => 'Reset Email is send successfully. Please check your email']);
   }

   public function validateEmail($email){
        return !!User::where('email', $email)->first();
   }

   public function send($email){
      $data  = array();
      $data['email'] = $email;
      $data['token'] = self::createToken($email);
      print_r($data['token']);

        Mail::send('emails.reset_password_email', ['token' => $data['token']] , function($message) use ($data){
      $message->to($data['email'])->from('fibitadesse@gmail.com')->subject('Password Reset');
    });
   }
   public function createToken($email){
        $oldToken = \DB::table('password_resets')->where('email', $email)->first();
        if($oldToken){
            return $oldToken->token;
        }
        $token = str_random(60);
        self::saveToken($token, $email);
        return $token;
   }
   public function saveToken($token, $email){
        \DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
   }
}
