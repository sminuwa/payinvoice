<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();
        $cards = Card::where('user_id', $user->id);
        if($cards){
            return ['status' => 1, 'data' => $cards];
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $card = new Card();
        $card->user_id = $user->id;
        $card->number = rand(1000000000, 999999999);
        $card->pin = $request->pin;
        $card->type = 1;
        if($card->save()){
            return ['status' => 1, 'message'=>'Card created successfully'];
        }
        return ['status' => 0, 'message'=>'Something went wrong'];
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        return $user;
    }
}
