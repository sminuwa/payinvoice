<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class eNaira
{

    const BASE_URL = 'https://rgw.k8s.apis.ng/centric-platforms/uat';


    public static function clientInstance(): Client
    {
        return new Client([
            'base_url' => eNaira::BASE_URL,
            'protocols' => ['https']
        ]);
    }

    public static function request($url, $type, $header, $body)
    {
        return self::clientInstance()->client->request(
            $type, $url, [
                'headers' => array_merge(
                    [
                        'ClientId' => env('ENAIRA_CLIENT_ID'),
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ],
                    $header
                ),
                'json' => $body
            ]
        );
    }

    // get balance
    public static function getBalance()
    {

    }
    //create account


}
