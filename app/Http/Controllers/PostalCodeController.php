<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientPostalCode;
use App\Models\Client;
use Session;

class PostalCodeController extends Controller
{
    public function validatePostalCode(Request $request)
    {
        Session::forget('mincmd');
        if ($request->hasCookie('ccmode')){
            if($_COOKIE['ccmode']){
            
                session()->put('showPopup', false);
                session()->put('mincmd', 0);
            }
        }
        else if ($request->hasCookie('deliverymode')){

        
                $restaurant_id = env('Restaurant_id');
                //$code = $request->input('postal_code');
                $code= $_COOKIE['postal_code'];
                // Check if the code exists in the database
                $fcode= substr($code,0,2);

                $codeExists = ClientPostalCode::where('postal_code','like' , $fcode.'%')
                                                ->where('client_id', $restaurant_id)
                                                ->exists();
                
                // Set the session variable based on the code validation
                if ($codeExists) {
                    session()->put('showPopup', false);
                    $min_cmd = ClientPostalCode::where('postal_code','like' , $fcode.'%')
                    ->where('client_id', $restaurant_id)->get();
                    //dd($min_cmd);
                    
                    if ($min_cmd){
                        $mincmd=$min_cmd->first()->min_cmd;
                        session()->put('mincmd',$mincmd);
                    }
                    
                } else {
                    
                    session()->put('showPopup', true);
                }
            }
        // Redirect back to the previous page
        return redirect()->back();
    }

    
}
