<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request){
        $user = $request->user();
        return [
            'id' => $user->id,
            'surname' => $user->surname,
            'firstname' => $user->firstname,
            'othernames' => $user->othernames,
            'email' => $user->email,
            'type' => $user->type,
        ];
    }

    public function getBalance(){
        return eNaira::getBalance();
    }
}
