<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PasswordRestController extends Controller
{
   public function sendEmail(Request $request){

    self::send($request);
   }

   public function send($request){
   	Mail::send('emails.reset_password_email',['name' => 'me'] , function($message){
   		$message->to('feventadesse2@gmail.com', 'someguy')->from('fibitadesse@gmail.com')->subject('Password Reset');
   	});
   }
}
