<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {

        $request->validate([
            'token' => "required",
            'email' => "required|email",
            'password' => "required|string|confirmed|min:8",

        ]);

        $status = Password::reset(
            $request->only(
                'email', 
            'password', 
            'passwordd_confirmation',
            'token'),

            function(User $user ,string $password){
                $user->forceFill(
                    ['password'=> bcrypt($password),
                    'remember_token'=> Str::random(60),
                    
                ])->save();
                }

        );
        
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)], 200)
            : response()->json(['status' => __($status)], 400);
    


    }
}
