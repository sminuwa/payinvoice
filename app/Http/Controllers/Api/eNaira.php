<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class eNaira
{

    const BASE_URL = 'http://rgw.k8s.apis.ng/centric-platforms/uat';


    public static function clientInstance(): Client
    {
        $client =  new Client([
            'base_url' => eNaira::BASE_URL,
            'protocols' => ['http']
        ]);


        return $client;
    }

    public static function request($url, $header, $body)
    {
        return Http::baseUrl(eNaira::BASE_URL)
            ->withoutVerifying()
            ->withHeaders(array_merge(
                [
                    'ClientId' => env('ENAIRA_CLIENT_ID'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                $header
            ),)
            ->withBody(
                json_encode($body),"application/json"
            )->post($url)->json();

    }

    // get balance  $user_type (USER,MERCHANT)
    public static function getBalance($token,$email,$user_type="USER")
    {
        try{
            $body = [
                "user_email"=>$email,
                "user_token"=>$token,
                "user_type"=>strtoupper($user_type),
                "channel_code"=>"APISNG"
            ];
            $response = self::request("/GetBalance",[],$body);
            if($response['response_code']=="00"){
                return ["status"=>1,"data"=>$response['response_data']['wallet_balance']];
            }else if($response['response_code']=="99"){
                return ["status"=>2,"message"=>"User token Expired"];
            }else{
                return ["status"=>0,"message"=>"Oops! Something went wrong"];
            }
        }catch (\Exception $e){
            return ["status"=>0,"message"=>"Oops! Something went wrong".$e->getMessage()];
        }
    }
    //create account

    public static function login($email,$password,$user_type="USER")
    {//
        try{
            $body = [
                "user_id"=>$email,
                "password"=>$password,
                "allow_tokenization"=>"Y",
                "user_type"=>strtoupper($user_type),
                "channel_code"=>"APISNG"
            ];
            $response = self::request("/CAMLLogin",[],$body);
            if($response['response_code']=="00"){
                return ['status'=>1,'data'=>$response['response_data']];//['wallet_balance'];
            }else{
                return ["status"=>0,"message"=>"Oops! Something went wrong"];
            }
        }catch (\Exception $e){
            return ["status"=>0,"message"=>"Oops! Something went wrong".$e->getMessage()];
        }
    }

    public static function getUserByPhone($phone_number,$user_type="USER")
    {
        //
        try{
            $body = [
                "phone_number"=>$phone_number,
                "user_type"=>strtoupper($user_type),
                "channel_code"=>"APISNG"
            ];
            $response = self::request("/enaira-user/GetUserDetailsByPhone",[],$body);
            if($response['response_code']=="00"){
                return ['status'=>1,'data'=>$response['response_data']];//['wallet_balance'];
            }else{
                return ["status"=>0,"message"=>"Oops! Something went wrong"];
            }
        }catch (\Exception $e){
            return ["status"=>0,"message"=>"Oops! Something went wrong".$e->getMessage()];
        }
    }

    public static function getUserByAlias($wallet_alias,$user_type="USER")
    {
        //
        try{
            $alias = "@".str_replace('@','',$wallet_alias);
            $body = [
                "wallet_alias"=>$alias,
                "user_type"=>strtoupper($user_type),
                "channel_code"=>"APISNG"
            ];
            $response = self::request("/enaira-user/GetUserDetailsByWalletAlias",[],$body);
            if($response['response_code']=="00"){
                return ['status'=>1,'data'=>$response['response_data']];//['wallet_balance'];
            }else{
                return ["status"=>0,"message"=>"Oops! Something went wrong"];
            }
        }catch (\Exception $e){
            return ["status"=>0,"message"=>"Oops! Something went wrong".$e->getMessage()];
        }
    }
}
