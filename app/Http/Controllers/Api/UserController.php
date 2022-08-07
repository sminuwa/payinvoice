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
            'type' => $user->type == 1 ? 'Individual' : 'Merchant',
        ];
    }

    public function getBalance(){
        return eNaira::getBalance();
    }

    public function balance(Request $request)
    {
        $token = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhbGlhcyI6IkBpbWJhaC4wMWc5dGsiLCJleHBpcnkiOiIyMDIyLTA4LTA2VDIzOjM1OjI2LjY5NTM3OFoiLCJpc3MiOiJnb25kb3IgY29tbWVyY2UiLCJvcmciOiJhY2Nlc3MiLCJ0b2tlbl90eXBlIjoiYWNjZXNzIiwidXNlcmlkIjoiMDFHOVRLVDBTWllCSkhCRURXVktGQ1ZCS0EifQ.hgBUR9oFRexA-0WK2es2uWcaYjxNTGXOhWY7Sbh2jw5j4VYPU3hhJWNIqrtaADtIobNmOOzR5u3Rdt-hLlVih1C3iCJg_vISK5W8uQo7nMaEhTuBvVrV9dEvXJkq5ww8i9hYTBcMpnFTvEbZVD39UG-IeCqaD2FpX7N7x0WlWKxueeUL0konpwaNqS5avleTefwgjQsVbhE1v7b2NXUYRHx8ERAylVIyd1Zcl1offamq861Ila0qOI0r-K_exyG70ZkDduv4qypL65bfHy2DivtVYFbbY_c6FxRR7LZCW0e3NiZcTxOthgKz8-IRH3WSlq38yeDRvwj_IU869LSDWH2Gk5KJz3ZO_cpZ-QegoxicubXfZ_IFXn2h7t3fDgkuxa_eNYGHAZ24_ckXIe09ikwj9sO4ZH_jJEozp-XD6AoOOCYCPhsCKfC3AFGHRoak05Vg_R2VJugq1vy66aC7r0nWsvqSc7XB-NAgyY95QNFdsHpcjis91lnUrdxdU3AXjV1KtiNrmRE523EQwHhq_tzjnb_7ccC3-W-h_ekEsuyTV58g1MEED4TCVuU8WKBNP7AomJ9LqL1Xfb1ueUsbrITKFcDWp9N82gX4QGZkCPhAiYPZjxbNExdZgZs0eWJov0hHyd6SgY-Jbu21uIisfNqnA_cVh0ROaQLLU4-zwCc";
//        return eNaira::login("mango@cbn.gov.ng","1234567890123");
//        return eNaira::getUserByPhone("08036349533");
        return eNaira::getUserByAlias("imbah.01g9tk");
    }
}
