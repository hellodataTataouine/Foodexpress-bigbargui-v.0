<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ClientPostalCode;
use Session;

class ValidatePostalCodeMiddleware
{
    public function handle($request, Closure $next)
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
            
        return $next($request);
    }
}
