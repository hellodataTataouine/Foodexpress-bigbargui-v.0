<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientRestaurat;
use App\Models\ReservationTable;
use App\Models\Seo;
use App\Models\Table;
use App\Models\LivraisonRestaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\Environment\Console;
class ReservationTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        
        $restaurant_id = env('Restaurant_id');

        $reservations = ReservationTable::where('restaurant_id', $restaurant_id)
        ->with('tables')
        ->paginate(10);
		//	dd($reservations);
        return view('restaurant.reservation.index', compact('reservations'));
       
    }
    public function indexClient()
    {
		 if (auth()->guard('clientRestaurant')->check()){
            $userId = auth()->guard('clientRestaurant')->id();
           if ($userId) {
		   $clientRestaurant = ClientRestaurat::findOrFail($userId);
		   }
			
			 } else{
			 $clientRestaurant = null;
		 }
         $restaurant_id = env('Restaurant_id');

        $client = Client::where('id', $restaurant_id)->firstOrFail();
        $livraisons = LivraisonRestaurant::where('restaurant_id', $client->id)->get();
        $cart = session()->get('cart', []);
        
	//	dd($client);
        $seo = Seo::where('client_id',$restaurant_id)->firstOrFail();
        return view('client.reservation', compact('client','seo', 'cart', 'livraisons','clientRestaurant'));
        }
      

    // Show the form for creating a new resource.
    public function create()
    {
        $restaurant_id = env('Restaurant_id');


        $clients = ClientRestaurat::where('restaurant_id', $restaurant_id)->get();
        $tables = Table::where('id_restaurant', $restaurant_id)->get();
        
        return view('restaurant.reservation.create', compact('clients' , 'tables'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {

        $restaurant_id = env('Restaurant_id');

        $ReservationTable = new ReservationTable();
       
        $ReservationTable->table_id = $request->table_id;
        $ReservationTable->client_id = $request->client_id;
        $ReservationTable->nbre_personnes = $request->nbre_personnes;
        $ReservationTable->heure_debut = $request->heure_debut;
        $ReservationTable->heure_fin = $request->heure_fin;
        $ReservationTable->statut = "confirmé";
        $ReservationTable->date = $request->date; 
        $ReservationTable->restaurant_id = $restaurant_id;  
        $ReservationTable->save();
       
        // Redirect to the reservation index page or another appropriate location
        return redirect()->route('restaurant.reservation.index');
    }

    public function storeClient(Request $request)
    {
       
    
		
       $restaurant_id = env('Restaurant_id');

		
        $client = Client::where('id', $restaurant_id)->firstOrFail();
		
		$livraisons = LivraisonRestaurant::where('restaurant_id', $restaurant_id)->get();
        $cart = session()->get('cart', []);
		if (auth()->guard('clientRestaurant')->check()){
            $clientId = auth()->guard('clientRestaurant')->id();
			
			}else{
		$clientId= 0;
		}
		 $ReservationTable = new ReservationTable();
		
        $ReservationTable->table_id = 1;
        $ReservationTable->client_id = $clientId;
		 $ReservationTable->ClientFName = $request->FirstName;
		 $ReservationTable->ClientLName = $request->LastName;
		 $ReservationTable->ClientPhone = $request->phoneNum1;
		$ReservationTable->ClientEmail = $request->Email;
        $ReservationTable->nbre_personnes = $request->nbre_personnes;
        $ReservationTable->heure_debut = $request->heure_debut;
        $ReservationTable->heure_fin = $request->heure_fin;
        $ReservationTable->statut = "Nouveau";
        $ReservationTable->date = $request->date; 
        $ReservationTable->restaurant_id = $client->id;  
        $ReservationTable->save();
$ClientFName = $request->Nom . ' ' . $request->Prénom;
      // dd($client);
      $data = [
        'clientFirstName' => $ReservationTable->ClientFName,
        'clientLastName' => $ReservationTable->ClientLName,
        'clientNum1' => $ReservationTable->ClientPhone,
        'clientAdresse' => "",
        'reservationId' => $ReservationTable->id,
        'dateReservation' => $ReservationTable->date,
        'heure_debut' => $ReservationTable->heure_debut,
        'heure_fin' => $ReservationTable->heure_fin,
       'nbpersonne'=>$ReservationTable->nbre_personnes,
        'clientEmail' => $ReservationTable->ClientEmail,
        'restaurantEmail' => $client->email,
         
        
                'restaurant' => $client->name,
    ];
    $subject = 'Demande de réservation';
// Store the email in the session

// Send the email using the Blade view
Mail::send('new_reservation_restaurant', $data, function ($message) use ($subject, $data) {
    $message->subject($subject)
        ->to($data['restaurantEmail']);
});
        // Redirect to the reservation index page or another appropriate location
        
        $seo = Seo::where('client_id',$restaurant_id)->firstOrFail();
       return view('client.booking_success', compact('client', 'cart', 'livraisons', 'ClientFName','seo'));
      
    }
		 
		
	

    // Display the specified resource.
    public function show(ReservationTable $reservation)
    {
        return view('restaurant.reservation.show', compact('reservation'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        
        
        $restaurant_id = env('Restaurant_id');

         $reservation =   ReservationTable::findOrFail($id);
         $clients = ClientRestaurat::where('restaurant_id', $restaurant_id)->get();
         $tables = Table::where('id_restaurant', $restaurant_id)->get();
         
      
        return view('restaurant.reservation.edit', compact('reservation', 'clients', 'tables'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $reservation = ReservationTable::find($id);
        // Add validation rules for your reservation data
        $validatedData = $request->validate([
            'table_id' => 'required',
            'client_id' => 'required',
            'nbre_Personnes' => 'required',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'date' => 'required',
            'statut' => 'required',
        ]);

        // Update the reservation record
        $reservation->update($validatedData);

        // Redirect to the reservation index page or another appropriate location
        return redirect()->route('restaurant.reservation.index');
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {

        $reservation = ReservationTable::findOrFail($id);
        $reservation->delete();

        // Redirect to the reservation index page or another appropriate location
        return redirect()->route('restaurant.reservation.index')->with('success', 'reservation supprimée avec succès.');
    }

    public function Availabletables(Request $request)
    {
        $restaurant_id = env('Restaurant_id');

         
        $client = Client::where('id', $restaurant_id)->firstOrFail();
        // Retrieve the reservation criteria from the request
        $nbrePersonnes = $request->input('nbre_personnes');
        $heureDebut = $request->input('heure_debut');
        $heureFin = $request->input('heure_fin');
        $date = $request->input('date');
    
        // Query the available tables based on the criteria
        $availableTables = Table::leftJoin('reservation_table', function ($join) use ($date, $heureDebut, $heureFin) {
            $join->on('tables.id', '=', 'reservation_table.table_id')
                ->where('reservation_table.date', '=', $date)
              
                ->where(function ($query) use ($heureDebut, $heureFin) {
                    $query->whereBetween('reservation_table.heure_debut', [$heureDebut, $heureFin])
                        ->orWhereBetween('reservation_table.heure_fin', [$heureDebut, $heureFin]);
                })
                ->where('reservation_table.statut', '=', 'confirmé');
        })
        ->where('tables.nbre_Personnes', '>=', $nbrePersonnes)
        
        ->whereNull('reservation_table.id') // Filter tables with no matching reservations
        ->where('tables.id_restaurant', '=', $client->id)
        ->select('tables.id', 'tables.designation', 'tables.photo', 'tables.nbre_personnes')
        ->get();
    
        // Return the list of available tables as JSON response
        return response()->json($availableTables);
    }
    

}
