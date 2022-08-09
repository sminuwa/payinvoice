<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();
        $cards = Card::where('user_id', $user->id)->get();
        if($cards){
            return ['status' => 1, 'cards' => $cards];
        }
        return ['status' => 0, 'message'=>'Something went wrong'];
    }

    public function show(Request $request)
    {
        $card = Card::find($request->card_id);
        if($card){
            return ['status' => 1, 'card' => array_merge($card->toArray(), ['transactions'=>$card->transactions])];
        }
        return ['status' => 0, 'message'=>'Something went wrong'];
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $card = new Card();
        $card->user_id = $user->id;
        $card->number = rand(10000000, 99999999);
        $card->name = $request->name;
        $card->pin = $request->pin;
        $card->type = $user->type == User::TYPE_MERCHANT ? User::TYPE_MERCHANT : User::TYPE_USER;
        if($card->save()){
            return ['status' => 1, 'message'=>'Card created successfully'];
        }
        return ['status' => 0, 'message'=>'Something went wrong'];
    }

    public function transactions(Request $request)
    {
        $card_id = $request->card_id;
        $transactions = CardTransaction::where('card_id', $card_id)->get();
        if($transactions){
            return ['status' => 1, 'transactions' => $transactions];
        }
        return ['status' => 0, 'message'=>'Something went wrong'];
    }

}
