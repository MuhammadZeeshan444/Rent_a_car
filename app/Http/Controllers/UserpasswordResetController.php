<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserpasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;

class UserpasswordResetController extends Controller
{
    public function send_reset_password_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        //check request email exists or not
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response([
                'message' => 'Email does not exists!',
                'status' => 'error'
            ]);
        }
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // generat password reset token
        $token = Str::random(60);
        // saving data to password reset table
        UserPasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        // send password reset email view
        Mail::send('user-reset-password', ['token' => $token], function(Message $message) use($user){
            $message->subject('Reset Your Password');
            $message->to($user->email);
        });

        return response([
            'message' => 'Password reset email sent..check link',
            'status' => 'success'
        ]);

    }

    public function password_reset(Request $request, $token){
        //delete token after older then 2 minutes
        $formated = Carbon::now()->subMinute(2)->toDateTimeString();
        UserpasswordReset::where('created_at', '<=', $formated)->delete();

        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);
        //matching token from $request(frontend) with our table
        $passwordreset = UserpasswordReset::where('token', $token)->first();
        if(!$passwordreset){
            return response([
                'message' => 'Token is invalid or expire',
                'status' => 'error'
            ]);
        }
        //getting that user data whose email match from password reset table email
        $user = User::where('email', $passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        //deleting the token after reseting password
        UserpasswordReset::where('email', $user->email)->delete();

        return response([
            'message' => 'Password reset successfully',
            'status' => 'success'
        ]);
    }
}
