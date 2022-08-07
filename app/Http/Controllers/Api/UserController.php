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
        return [
            'id' => $user->id,
            'surname' => $user->surname,
            'firstname' => $user->firstname,
            'othernames' => $user->othernames,
            'email' => $user->email,
            'type' => $user->type,
        ];
    }

    public function balance(Request $request){
        $user = $request->user();
        $phone = $user->phone;
        $password = $user->password;
        if($token = User::login($user->phone, $user->password)){
            return eNaira::getBalance($token, $user->email, $user->type);
            return $this->success([]);
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
        $token = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhbGlhcyI6IkBpbWJhaC4wMWc5dGsiLCJleHBpcnkiOiIyMDIyLTA4LTA3VDEwOjIzOjM0LjMyMTMzMloiLCJpc3MiOiJnb25kb3IgY29tbWVyY2UiLCJvcmciOiJhY2Nlc3MiLCJ0b2tlbl90eXBlIjoiYWNjZXNzIiwidXNlcmlkIjoiMDFHOVRLVDBTWllCSkhCRURXVktGQ1ZCS0EifQ.rdl_u7gmSMsXLo3TPTIPjrz0p8zRpM_-oMAeVBlW2zxxSW_MnnDTEsYRucxnCv78PIqcAmaCw5u8NSe5jrCnNiwrqem2lES4rzSonlWLlmcOygRIve5xjn3KIMlt2b3QweCzeFVOPvlWp3AFfQj5-MvikRtZA2GVTKFq2JP4DfFW1eVN_bx0t8D4MsUQvbEibKCmleptqooDThxyCkg6CMK-T1A2n_zgSHhp8XfDQLqy8jEpSxZGB3NIGBQ4Lt0CfU08WeFPFYRvCEdLMrrCYC3s9Yg61Fe72OqjwowD2lBlCTt12hvCp7RO4ODbv1Bmk4XQjakj4UtNfHauaiThMLPaGXt7lHGjdBpgYwcngAuRT7SSJT0NsJ2A7K8wygJVoopKr69_k2Ysy7uNJ7vzLBkFerVwB4ARaqL6JNWkY7Z4pctuU3u6vFbmUY5hq-m93yLOgDhLgwoh0c4ASX9aW9upSIkiLTnHPE9OK7B-rdy5jRNKCsr61E14ayET6rFr2QCllNnOMw_8VJoV3OAX1591Ij5rnqCnaJou9TIIVJGo-vV0umxCjq3kZwqwcpBpMxE-rmzCN9VcfgnoLfjpktEMa4hp1VWwKlP0xUkvH6SHNKJnqX1yQya6zuAVf4-TQV0dwU9nSsoUIIK07jWcL--Zy2ex7db5DszGGxtiBeU";
//        return eNaira::getBalance($token,$email);
//        return eNaira::login($email,$password);
//        return eNaira::getUserByPhone($phone);
//        return eNaira::getUserByAlias($currentUserAlias);
//        return eNaira::createInvoice($product_code,$invoice_amount,"simple Invoice");
//        return eNaira::payFromWallet($token,$email,$recipient,100,"Hakane");
//        return eNaira::getTransactions($token,$email);
//        return eNaira::createDeposit($token,$user_id,$email,1000,"Fund me",);
//        return eNaira::getBVN("22292907674");
        return eNaira::createConsumer("22292907674","00140299547","wisewindx","xaxbczczaaxx");
    }
}
