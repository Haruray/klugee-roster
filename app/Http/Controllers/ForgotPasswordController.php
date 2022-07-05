<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Session;


class ForgotPasswordController extends Controller
{
  public function getEmail()
  {

     return view('auth.passwords.email');
  }

 public function postEmail(Request $request)
  {
    $request->validate([
        'email' => 'required|email|exists:users',
    ]);

    $token = Str::random(64);

    DB::insert('insert into password_resets (email, token) values (?, ?)', [$request->email, $token]);

    Mail::send('auth.verify', ['token' => $token], function($message) use($request){
        $message->to($request->email);
        $message->subject('Reset Password Notification');
    });
    Session::flash('sukses','We have e-mailed your password reset link!');
    return back();
  }
}
