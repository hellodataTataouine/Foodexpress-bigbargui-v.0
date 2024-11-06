<?php

namespace App\Http\Controllers;

use App\Models\ClientRestaurat;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Phone;
use App\Models\LivraisonRestaurant;


use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;





class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $restaurant_id = env('Restaurant_id');

        $client = Client::where('id', $restaurant_id)->firstOrFail();
		$livraisons = LivraisonRestaurant::where('restaurant_id', $restaurant_id)->get();

   	//dd($client);
        $cartItems = Session::get('cart', []);
         $cart = session()->get('cart', []);
         $seo = Seo::where('client_id',$restaurant_id)->firstOrFail();
        return view('client.register',compact('client', 'cart','livraisons','seo'));
    }

    public function register(Request $request)
{
    $restaurant_id = env('Restaurant_id');
    $request->validate([
        'nom' => 'required',
        'prenom' => 'required',
        'password' => 'required|min:8',
        'addresse' => 'required',
        'num1' => 'required',
        'email' => 'required',
    ]);
 
    $client = Client::where('id', $restaurant_id)->firstOrFail();
    // Check if the email already exists
    $existingUser = ClientRestaurat::where('email', $request->email)->where('restaurant_id', $client->id)->first();

    if ($existingUser) {
        return redirect()->back()->withInput()->withErrors(['email' => 'Cette adresse email est déja utilisée.']);
    }
		
		
   
    $basicUser = new ClientRestaurat;
       
    $basicUser->FirstName = $request->nom;
    $basicUser->LastName = $request->prenom;
    $basicUser->ville = $request->ville;
    $basicUser->Address = $request->addresse;
    $basicUser->codepostal = $request->codePostal;
    $basicUser->phoneNum1 = $request->num1;
    $basicUser->phoneNum2 = $request->num2;
    $basicUser->email = $request->email;
    $basicUser->password = Hash::make($request->password);
    $basicUser->restaurant_id = $client->id; // Set the appropriate restaurantId here
//dd($basicUser);

    $basicUser->save();

        $data = [
            'clientFirstName' => $basicUser->FirstName,
            'clientLastName' => $basicUser->LastName,
            'clientNum1' => $basicUser->phoneNum1,
            'clientAdresse' => $basicUser->Address,
            'email' => $basicUser->email,
        ];

        Mail::send('registration_confirmation', $data, function ($message) use ($data) {
            $message->subject('Confirmation d\'inscription')
                    ->to($data['email']);
        });

        auth('clientRestaurant')->login($basicUser);

        return redirect()->intended('/');
    }

public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect(); 
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
			
        } catch (\Exception $e) {
			
            return redirect('/')->with('error', 'Google Sign-In failed. Please try again.');
        }
	
        $restaurant_id = env('Restaurant_id');

        $client = Client::where('id', $restaurant_id)->firstOrFail();
        $existingUser = ClientRestaurat::where('email', $user->email)->where('restaurant_id', $client->id)->first();
    
        if ($existingUser) {
            auth('clientRestaurant')->login($existingUser);
        } else {
           

            $newuser = new ClientRestaurat();
            $fullName = explode(' ', $user->name); 
            $newuser->FirstName = $fullName[0]; 
            $newuser->LastName = $fullName[1] ?? ''; 
            $newuser->email = $user->email;
            $newuser->password = bcrypt(Str::random(16));
    
            $newuser->ville = null;
            $newuser->Address = null;
            $newuser->codepostal = null;
            $newuser->phoneNum1 = null;
            $newuser->phoneNum2 = null;
    

            $newuser->restaurant_id = $client->id;
    
            $newuser->save();
			 $cartItems = Session::get('cart', []);
            $cart = session()->get('cart', []);
            $livraisons = LivraisonRestaurant::where('restaurant_id', $client->id)->get();
            auth('clientRestaurant')->login($newuser);
           return view('client.edit_profile', compact('newuser', 'client', 'cart', 'livraisons'));
        }
    
        return redirect('/');
    }




    public function redirectToFacebook()
{
    return Socialite::driver('facebook')->redirect();
}

public function handleFacebookCallback()
{
    try {
        $user = Socialite::driver('facebook')->user();
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Facebook Sign-In failed. Please try again.');
    }

    $existingUser = User::where('email', $user->email)->first();

    if ($existingUser) {
        Auth::login($existingUser);
    } else {
        $newUser = new User();
        $newUser->name = $user->name;
        $newUser->email = $user->email;
        $newUser->password = bcrypt(Str::random(16));
        $newUser->save();

        Auth::login($newUser);
    }

    return redirect('/dashboard');
}

public function editProfile()
    {
        $restaurant_id = env('Restaurant_id');

        $client = Client::where('id', $restaurant_id)->firstOrFail();

		$livraisons = LivraisonRestaurant::where('restaurant_id', $client->id)->get();

	     $cartItems = Session::get('cart', []);
         $cart = session()->get('cart', []);
    
    $newuser = auth('clientRestaurant')->user();
	
    return view('client.edit_profile', compact('newuser', 'client', 'cart', 'livraisons'));
}

public function updateProfile(Request $request)
{
    $user = auth('clientRestaurant')->user();
//dd($user);
$restaurant_id = env('Restaurant_id');

    $client = Client::where('id', $restaurant_id)->first();
    
       
    //$user->FirstName = $request->nom;
    //$user->LastName = $request->prenom;
    $user->ville = $request->ville;
    $user->Address = $request->Address;
    $user->codepostal = $request->codepostal;
    $user->phoneNum1 = $request->phoneNum1;
    $user->phoneNum2 = $request->phoneNum2;
    //$user->email = $request->email;
    $user->restaurant_id = $client->id; // Set the appropriate restaurantId here


    $user->save();
	 $host = request()->getHost();
       
        
        $cartItems = Session::get('cart', []);
         $cart = session()->get('cart', []);

 return redirect('/');
  
}





}







