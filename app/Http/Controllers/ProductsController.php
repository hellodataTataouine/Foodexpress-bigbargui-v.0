<?php

namespace App\Http\Controllers;

use App\Models\ClientPostalCode;
use App\Models\Horaire;
use App\Models\JourFerier;
use App\Models\LivraisonMethod;
use App\Models\Seo;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Client;
use App\Models\ProduitsRestaurants;
use App\Models\CategoriesRestaurant;
use App\Models\LivraisonRestaurant;
use App\Models\familleOptionsRestaurant;
use App\Models\UserProduct;
use Illuminate\Pagination\Paginator;
class ProductsController  extends controller
{

    public function index()
    {
        $restaurant_id = env('Restaurant_id');

        $client = Client::where('id', $restaurant_id)->firstOrFail();
   
        // Retrieve the categories that exist in the produits table
        $categories = CategoriesRestaurant::where('restaurant_id', $restaurant_id)->get();
		
		$livraisons = LivraisonRestaurant::where('restaurant_id', $restaurant_id)->get();
        $livraisonids=$livraisons->pluck('livraison_id')->toArray();
        $livraisonMethode= LivraisonMethod::whereIn('id',$livraisonids)->get();
        $categoryIds = $categories->pluck('id')->toArray();
    
        // Retrieve the first product for each category
        $firstProducts = [];
        foreach ($categoryIds as $categoryId) {
            $firstProduct = ProduitsRestaurants::where('categorie_rest_id', $categoryId)->first();
            if ($firstProduct) {
                $firstProducts[$categoryId] = $firstProduct;
            }
        }
            Paginator::defaultView('client.layouts.custom-paginator');
    
        // Retrieve the products from the produits table with matching IDs
        $products = ProduitsRestaurants::whereIn('categorie_rest_id', $categoryIds)->where('status', 1)->where('restaurant_id', $restaurant_id)->get();
     
    // Pagination
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 1000; // Number of products per page
    $currentPageProducts = $products->forPage($currentPage, $perPage);
    $paginator = new LengthAwarePaginator($currentPageProducts, $products->count(), $perPage);
    $paginator->setPath(route('client.products.index'));

        // Retrieve the famille options for the products
        $familleOptions = familleOptionsRestaurant::whereIn('categorie_rest_id', $categoryIds)->with('options')->get();
        
        $cart = session()->get('cart', []);
        
        if ($comefrom = Cookie::get('comefrom')){
            if(Cookie::get('ccmode') || Cookie::get('deliverymode')){
                return redirect()->to($comefrom);
            }
            
        }
        $horaire = Horaire::where('client_id' , $restaurant_id)
        ->where("date_debut", "<=",date('Y-m-d'))
        ->where("date_fin", ">=",date('Y-m-d'))->get();
        $jourfrier = JourFerier::where('client_id',$restaurant_id)->get();
        $seo = Seo::where('client_id',$restaurant_id)->firstOrFail();
        return view('client.index', compact('client','horaire','seo' ,'jourfrier', 'livraisonMethode','products', 'categories', 'livraisons', 'firstProducts', 'familleOptions', 'cart', 'paginator'));
    }
    public function home()
    {
        $restaurant_id = env('Restaurant_id');

        $client = Client::where('id', $restaurant_id)->firstOrFail();
        $cart = session()->get('cart', []);
        $livraisons = LivraisonRestaurant::where('restaurant_id', $restaurant_id)->get();
        return view('client.home', compact('client', 'livraisons','cart'));


    }

    public function getHoraires(Request $request){
        $restaurant_id = env('Restaurant_id');
        $horaire = Horaire::where('client_id' , $restaurant_id)
        ->where("date_debut", "<=",$request->date)
        ->where("date_fin", ">=",$request->date)->get();
        return response()->json(array('horaire'=> $horaire));

    }




    
}
