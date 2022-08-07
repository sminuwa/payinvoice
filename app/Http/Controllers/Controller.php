<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function scc($data, $message=""){
        return [
            'status'=>1,
            'message'=>$message,
            'data' => $data
        ];
    }
    public function err($message=""){
        return [
            'status'=>0,
            'message'=>$message,
        ];
    }
}
