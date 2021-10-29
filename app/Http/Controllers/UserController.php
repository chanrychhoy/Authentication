<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);

        $user->save();
        $token=$user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token
        ]);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'User logged out']);
    }

    public function login(Request $request){

        $user=User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['message'=>'bad login'],401);
        }
        
        $token=$user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);

    }
}
