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
            $request->validate([
                'phone' => 'required',
                'password' => 'required'
            ]);
            $phone = $request->phone;
            $password = $request->password;
            if($login = User::login($phone, $password)){
                $user = User::where('phone', $phone)->first();
                $token = $user->createToken('API Token')->plainTextToken;
                return $this->success($token, 'Successfully');
            }
            return $this->err('Invalid Credentials.');
        }catch(\Exception $e){
            return $this->err("Something went wrong ". $e->getMessage());
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
            return $this->success(
                $user->createToken('tokens')->plainTextToken
            );
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



}
