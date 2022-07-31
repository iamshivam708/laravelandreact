<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Models\User;
use App\Models\Product;
use App\Models\Offer;

Route::prefix('user')->group(function(){
    Route::post('/signup',[\App\Http\Controllers\UserController::class,'signup']);
    Route::post('/login',[\App\Http\Controllers\UserController::class,'login']);
});

Route::prefix('product')->group(function(){
    Route::post('/create',[\App\Http\Controllers\ProductController::class,'create']);
    Route::get('/get/{email}',[\App\Http\Controllers\ProductController::class,'getProductByEmail']);
    Route::post('/update/{id}',[\App\Http\Controllers\ProductController::class,'update']);
    Route::get('/getAll/{email}',function($email){
        $products = (new Product)::where('email','!=',$email)->orderBy('id', 'DESC')->get();
        return response()->json(['products'=>$products]);
    });
    Route::get('/single/{id}',function($id){
        $product = (new Product)::where('id',$id)->get();
        return response()->json(['product'=>$product]);
    });
    Route::get('/delete/{id}',function($id){
        (new Product)::where('id',$id)->delete();
        $product = (new Product)::where('id',$id)->get();
        $result = $product[0]->getOriginal();
        if(file_exists('../oldcoin/public/uploads/'.$result['image'])){
            unlink('../oldcoin/public/uploads/'.$result['image']);
            return response()->json(['message'=>'product deleted successfully']);
        }else{
            return response()->json(['message'=>'image not found']);
        }
    });
});

Route::post('/offer/create',[\App\Http\Controllers\ProductController::class,'createOffer']);
Route::get('/offer/{email}',function($email){
    $offers = (new Offer)::where('email',$email)->get();
    return response()->json(['offers'=>$offers]);
});

Route::get('/offer/product/{id}',function($id){
    $offers = (new Offer)::where('productId',$id)->get();
    return response()->json(['offers'=>$offers]);
});


Route::get("/user/details/{email}",function($email){
    $user = (new User)::where('email',$email)->get();
    return response()->json(['user' => $user]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
