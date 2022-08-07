<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request){
        $user = $request->user();
        return $user;
        if($details = eNaira::getUserByPhone($user->phone,$user->type)) {
            return $details;
        }
        return $this->err('Something went wrong');
    }

    public function balance(Request $request){
        $user = $request->user();
        if($token = User::login($user->phone, $user->password)){
            return eNaira::getBalance($token, $user->email, $user->type);
        }
        return $this->err('Something went wrong');
    }

    public function wallet(Request $request)
    {
        $user = $request->user();
        if($wallet = eNaira::getUserByPhone($user->phone,$user->type)) {
            return $wallet['data']['wallet_info'];
        }
        return $this->err('Something went wrong');
    }

    public function transactions(Request $request){
        $user = $request->user();
        $t = [];
        if($token = User::login($user->phone, $user->password)){
            $transactions = eNaira::getTransactions($token,$user->email,$user->type);
            foreach($transactions['response_data'] as $transaction){
                $t[] = [
                    'id'=>$transaction['cursor'],
                    'amount'=>$transaction['node']['amount'],
                    'currencyCode'=>$transaction['node']['currencyCode'],
                    'currentState'=>$transaction['node']['currentState'],
                    'feeAmount'=>$transaction['node']['feeAmount'],
                    'guid'=>$transaction['node']['guid'],
                    'insertedAt'=>$transaction['node']['insertedAt'],
                    'invoiceGuid'=>$transaction['node']['invoiceGuid'],
                    'transactionWalletAddress'=>$transaction['node']['transactionWalletAddress'],
                    'type'=>$transaction['node']['type'],
                    'updatedAt'=>$transaction['node']['updatedAt'],
                ];
            }
            return $t;
        }
        return $this->err('Something went wrong');
    }

    public function balance1(Request $request)
    {
        $currentUserAlias = "imbah.01g9tk";
        $recipient = "@imbah.01";
        $user_id = "mango@cbn.gov.ng";
        $email = "mango@cbn.gov.ng";
        $phone = "08036349533";
        $password = "1234567890123";
        $product_code = "001";
        $invoice_amount = 200;
        $token = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhbGlhcyI6IkBpbWJhaC4wMWc5dGsiLCJleHBpcnkiOiIyMDIyLTA4LTA3VDE1OjA4OjA3LjY0MzUwMFoiLCJpc3MiOiJnb25kb3IgY29tbWVyY2UiLCJvcmciOiJhY2Nlc3MiLCJ0b2tlbl90eXBlIjoiYWNjZXNzIiwidXNlcmlkIjoiMDFHOVRLVDBTWllCSkhCRURXVktGQ1ZCS0EifQ.dTm3j-xDqgPasoePeY5Alv3p2o70oQ2OSBU91uv3RbYAF4-bgtP34k9FQGmwHi7LQvDI57rwYnvFUjprQ4oKgzwrG_IUab5eEVv0LNmmsPspJSSddHwa2lNDnnqh9CfsI1Sg0UpyoHJoTwtfP1J_dYYUFQfb2Rr5eA5T-fOquDQt3CINwLYESzRt92Cqhzd1UdKnAumvat6IQYtFGXf8h8iItg1haJtsuixz6M-cyCLyShS4oyLvsfgqkUY3kxhb7tgdMSlf63QPh5Ebjar1J_u__dPbyLQcL3FyqujKlfkzaAFulovv7O8aM5dQg4pkpqhwHq5WaAcrzwtE9n6RoHkJpYP7ztaP-qjUwG6v7peypLrkWc-qGkUxj7Nxh3doB3j22lvYKR577kalEAar3rKH5Vlib7PpdssnG6dFbj5bEyCKvdV87u0MWKbOO1mHM-DwHaUpthJ4LcQmq0yG_ujmyhEk-Wfb7cLpp776WyYOTXlFyID5TTKh3WcsaLOEY9SPmG-O8cFCG-GJMM-r3DpKVhJi5f2j8wckgnGFtBSmOYp_oR6XVk_hK3-SuNaB1mebEu2yN35tkSfeMVGeFW0-23XJOvwQEdQPNv4b5l3CYZ-feQuMNzj2aO_oFWEhC0yEQLZkyTQWA8bXE6X_k3UFs4L22BAfmW8hsNAK1YM";
//        return eNaira::getBalance($token,$email);
//        return eNaira::login($email,$password);
//        return eNaira::getUserByPhone($phone);
//        return eNaira::getUserByAlias($currentUserAlias);
//        return eNaira::createInvoice($product_code,$invoice_amount,"simple Invoice");
//        return eNaira::payFromWallet($token,$email,$recipient,100,"Hakane");
//        return eNaira::getTransactions($token,$email);
//        return eNaira::createDeposit($token,$user_id,$email,1000,"Fund me",);
//        return eNaira::getBVN("22292907674");
        return eNaira::createConsumer("22190373502","0052929034","sminuwa","xaxbczczaaxx");
    }
}
