<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        try{
            $attr = $request->validate([
                'phone' => 'required',
                'password' => 'required'
            ]);
            $credentials = ["phone"=>$request->phone,"password"=>$request->password];
            if (Auth::attempt($credentials, 0)) {
                $token = auth()->user()->createToken('API Token')->plainTextToken;
                return $this->success(['token' => $token ]);
            }
            return $this->err('Invalid Credentials.');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function register(Request $request){
        try{
            $user = User::where('phone', $request->phone)->first();
            if(!$user)
                $user = new User();
            $user->surname = $request->surname;
            $user->firstname = $request->firstname;
            $user->othernames = $request->othernames;
            $user->bvn = $request->bvn;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return $this->success([
                'token' => $user->createToken('tokens')->plainTextToken
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Tokens Revoked'
        ];
    }

    public function success($data, $message=""){
        return [
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ];
    }
    public function err($message=""){
        return [
            'status'=>0,
            'message'=>$message,
        ];
    }

}
