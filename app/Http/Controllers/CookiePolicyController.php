<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LivraisonRestaurant;


class CookiePolicyController extends Controller
{
    public function show(Request $request)
{
    $restaurant_id = env('Restaurant_id');

 $client = Client::where('id', $restaurant_id)->firstOrFail();
		$livraisons = LivraisonRestaurant::where('restaurant_id', $client->id)->get();

	    $cart = session()->get('cart', []);
	  $user = User::findOrFail($client->user_id);

		
        return view('client.politique-de-cookies', compact('client', 'cart', 'user', 'livraisons'));
    }
}
