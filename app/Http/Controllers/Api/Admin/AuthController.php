<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $user = $request->validate([
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'required|regex:/^(?=.*[A-Za-z\d])[A-Za-z\d]{8,}$/',
            ]);
            
            User::create($user);

            return response()->json(['message'=>'success'],201);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
        
    }

    public function login(Request $request){
        try {
            $user = $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
            $userDb = User::query()->where('email',$user['email'])->where('isAdmin',true)->first();
            if (empty($userDb)){
                return  response()->json(['message'=>'Email hoặc mật khẩu không chính xác',],400);
            }
            if (Hash::check($user['password'],data_get($userDb,'password'))){
                $token = $userDb->createToken('token')->plainTextToken;
                return response()->json(['message'=>'success','token'=>$token,'user'=>$userDb],200);
            }
            return response()->json(['message'=>'Email hoặc mật khẩu không chính xác',],400);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function logout(){
        try {
            auth()->user()->tokens()->delete();
            return response()->json(['message'=>'logged out'],200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function authorizeAdmin(Request $request){
        return $request->user();
    }
}
