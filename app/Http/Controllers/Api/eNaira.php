<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class eNaira
{

    const BASE_URL = 'http://rgw.k8s.apis.ng/centric-platforms/uat';


    public static function clientInstance(): Client
    {
        $client = new Client([
            'base_url' => eNaira::BASE_URL,
            'protocols' => ['http']
        ]);


        return $client;
    }

    public static function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
                json_encode($body), "application/json"
            )->post($url, [
                'timeout' => 15
            ])->json();

    }

    // get balance  $user_type (USER,MERCHANT)
    public static function getBalance($token, $email, $user_type = "USER")
    {
        try {
            $body = [
                "user_email" => $email,
                "user_token" => $token,
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            $response = self::request("/GetBalance", [], $body);
            if ($response['response_code'] == "00") {
                return ["status" => 1, "data" => $response['response_data']['wallet_balance']];
            } else if ($response['response_code'] == "99") {
                return ["status" => 2, "message" => "User token Expired"];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    //create account

    public static function login($email, $password, $user_type = "USER")
    {//
        try {
            $body = [
                "user_id" => $email,
                "password" => $password,
                "allow_tokenization" => "Y",
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            return $response = self::request("/CAMLLogin", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function getUserByPhone($phone_number, $user_type = "USER")
    {
        //
        try {
            $body = [
                "phone_number" => $phone_number,
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            $response = self::request("/enaira-user/GetUserDetailsByPhone", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function getUserByAlias($wallet_alias, $user_type = "USER")
    {
        //
        try {
            $alias = "@" . str_replace('@', '', $wallet_alias);
            $body = [
                "wallet_alias" => $alias,
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            $response = self::request("/enaira-user/GetUserDetailsByWalletAlias", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function createInvoice($product_code, $amount, $narration)
    {
        //CreateInvoice
        try {
            $body = [
                "amount" => $amount,
                "narration" => $narration,
                "product_code" => $product_code,
                "reference" => "PICI" . self::generateRandomString(3) . time(),
                "channel_code" => "APISNG"
            ];
            $response = self::request("/CreateInvoice", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function payFromWallet($token,$email,$recipient_wallet,$amount,$narration,$user_type="USER")
    {
        //
        try {
            $alias = "@" . str_replace('@', '', $recipient_wallet);
            $body = [
                "user_token" => $token,
                "user_email" => $email,
                "destination_wallet_alias" => $alias,
                "amount" => $amount,
                "reference" => "PIPW" . self::generateRandomString(3) . time(),
                "narration" => $narration,
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            $response = self::request("/PaymentFromWallet", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            }elseif ($response['response_code'] =="99"){
                return ['status' => 0, 'message' => $response['response_code']['Data']['message']];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function getTransactions($token,$email,$user_type="USER")
    {

        try {
            $body = [
                "user_email" => $email,
                "user_token" => $token,
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            return $response = self::request("/GetTransactions", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            }elseif ($response['response_code'] =="99"){
                return ['status' => 0, 'message' => $response['response_code']['Data']['message']];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function createDeposit($token,$user_id,$email,$amount,$narration,$account_no="0760261888",$user_type="USER")
    {
        try {
            $body = [
                "user_id" => $user_id,
                "user_token" => $token,
                "account_no" => $account_no,
                "amount" => $amount,
                "user_email" => $email,
                "reference" => "PICD" . self::generateRandomString(3) . time(),
                "narration" => $narration,
                "user_type" => strtoupper($user_type),
                "channel_code" => "APISNG"
            ];
            $response = self::request("/CreateDeposit", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            }elseif ($response['response_code'] =="99"){
                return ['status' => 0, 'message' => $response['response_code']['Data']['message']];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }

    public static function getBVN($bvn)
    {
        try {
            $body = [
                "bvn" => $bvn,
                "channel_code" => "APISNG"
            ];
            $response = self::request("/customer/identity/BVN", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            }elseif ($response['response_code'] =="99"){
                return ['status' => 0, 'message' => $response['response_code']['Data']['message']];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage().$e->getTraceAsString()];
        }
    }

    public static function createConsumer($bvn,$account_no,$username,$password)
    {
        try {
            $bvnDetails = self::getBVN($bvn);
            if($bvnDetails['status']!=1){
                return ["status" => 0, "message" => "Invalid BVN" ];
            }
            $userDetails = (object)$bvnDetails['data'];
            $date = Carbon::createFromFormat("d-M-Y",$userDetails->dateOfBirth)->format('d/m/Y');
            $body = [
                "uid"=>$bvn,
                "uidType"=>"BVN",
                "reference" => "PICC" . self::generateRandomString(3) . time(),
                "title"=>"Mr",
                "firstName"=>$userDetails->firstName,
                "middleName"=>$userDetails->middleName,
                "lastName"=>$userDetails->lastName,
                "userName"=>$userDetails->email,
                "phone"=>$userDetails->phoneNumber1,
                "emailId"=>$userDetails->email,
                "postalCode"=>"900110",
                "city"=>$userDetails->lgaOfResidence,
                "address"=>$userDetails->residentialAddress,
                "countryOfResidence"=>"NG",
                "tier"=>2,
                "password"=>$password,
                "accountNumber"=>$account_no,
                "dateOfBirth"=>$date,
                "countryOfBirth"=>"NG",
                "remarks"=>"Passed",
                "referralCode"=>"@imbah.01",
                "user_type" => strtoupper("USER"),
                "channelCode" => "APISNG"
            ];
            return $response = self::request("/enaira-user/CreateConsumerV2", [], $body);
            if ($response['response_code'] == "00") {
                return ['status' => 1, 'data' => $response['response_data']];//['wallet_balance'];
            }elseif ($response['response_code'] =="99"){
                return ['status' => 0, 'message' => $response['response_code']['Data']['message']];
            } else {
                return ["status" => 0, "message" => "Oops! Something went wrong"];
            }
        } catch (\Exception $e) {
            return ["status" => 0, "message" => "Oops! Something went wrong" . $e->getMessage()];
        }
    }
}
