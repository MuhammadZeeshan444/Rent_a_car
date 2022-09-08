<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);
        if(User::where('email', $request->email)->first())
        {
            return response([
                'message' => 'User with this email already exists',
                'status' => 'error'
            ]);
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken($request->email)->plainTextToken;
            return response([
                'user_token' => $token,
                'message' => 'Registrated khjhk',
                'status' => 'success'
            ]);
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if($user)
        {
            if(Hash::check($request->password, $user->password))
            {
                $token = $user->createToken($request->email)->plainTextToken;
                return response([
                    'user_token' => $token,
                    'message' => 'Login successfully',
                    'status' => 'success'
                ]);
            }
        }
        return response([
            'message' => 'These credentails do not match our record!',
            'status' => 'error'
        ]);
    }

    public function logout(){
        if(Auth()->user()->tokens()->delete())
        {
            return response([
                'message' => 'Logout successfully!',
                'status' => 'success'
            ]);
        }
        return response([
            'message' => 'Something went wrong!',
            'status' => 'error'
        ]);
    }
    public function logged_user(){
        $user = Auth()->user();
            return response([
                'user' => $user,
                'message' => 'Logged in user all data',
                'status' => 'success'
            ]);
    }

    public function change_password(Request $request){
        $request->validate([
            'password' => 'required|confirmed|min:4',
        ]);
        $user = Auth()->user();
        $user->password = Hash::make($request->password);
        // var_dump($request->all());
        $user->save();
        return response([
            'message' => 'password changed successfully',
            'status' => 'success'
        ]);
    }
}
