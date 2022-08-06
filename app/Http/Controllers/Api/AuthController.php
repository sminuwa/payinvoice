<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
                return $this->success( ['token' => auth()->user()->createToken('API Token')->plainTextToken ]);
            }
            return $this->err('Credentials not match', 401);
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
            'status'=>'Success',
            'code'=>200,
            'message'=>$message,
            'data'=>$data
        ];
    }
    public function err($message="", $code=101){
        return [
            'status'=>'Error',
            'message'=>$message,
            'code'=>$code,
        ];
    }
}
