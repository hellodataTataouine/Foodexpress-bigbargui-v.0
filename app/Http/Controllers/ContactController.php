<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LivraisonRestaurant;

use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
   public function showContactForm(Request $request)
{
    $restaurant_id = env('Restaurant_id');
    $client = Client::where('id', $restaurant_id)->firstOrFail();
	   		$livraisons = LivraisonRestaurant::where('restaurant_id', $restaurant_id)->get();

	    $cart = session()->get('cart', []);
	   
	   $user = User::findOrFail($client->user_id);
	  
	       
	   
    return view('client.contact', compact('client', 'cart', 'user', 'livraisons'));
}

public function submitContactForm(Request $request)
{
	
    $restaurantId = env('Restaurant_id');
    $user = User::findOrFail($restaurantId);
    $email = $user->email;
	$subject = 'Formulaire de Contact';
	$currentDateTime = now()->format('d/m/Y H:i:s');
	
	
	//$testEmail = 'firas.saafi96@gmail.com';




    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'message' => 'required|string',
    ]);

 
  //  Mail::to('admin@email.com')->send(new ContactFormMail($validatedData));
	  Mail::send('contact_email', ['data' => $validatedData, 'currentDateTime' => $currentDateTime], 
				 function ($message) use ($subject, $email) {
            $message->subject($subject)->to($email);
		   	

});
	


   
    return redirect()->back()->with('success', 'Votre message a été envoyé avec succès.');
}

}
