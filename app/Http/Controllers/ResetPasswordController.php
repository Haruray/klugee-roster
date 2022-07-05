<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;

class ResetPasswordController extends Controller {

  public function getPassword($token) {

     return view('auth.passwords.reset', ['token' => $token]);
  }

  public function updatePassword(Request $request){


    $updatePassword = DB::table('password_resets')
                        ->where(['email' => $request->email, 'token' => $request->token])
                        ->first();

    if(!$updatePassword){
        return response()->json([
            'success' => false,
            'message' => 'Invalid Token.'
        ], 401);
    }


    $user = User::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

    DB::table('password_resets')->where(['email'=> $request->email])->delete();

    return response()->json([
        'success' => true,
        'data' => User::where('email',$request->email)->get()->first(),
    ], 200);

  }
}
