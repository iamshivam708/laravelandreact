<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Offer;

class ProductController extends Controller
{
    public function create(Request $request){
        $file = $request->file('image');
        $destination = '../oldcoin/public/uploads';
        if($file->move($destination,$file->getClientOriginalName())){
            $data = [
                'image' => $file->getClientOriginalName(),
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'email' => $request->get('email')
            ];
            $result = (new Product)::create($data);
            if($result){
                return response()->json([
                    'message'=>'Product Created Successfully!!'
                ]);
            }else{
                return response()->json([
                    'message'=>'Error Occurred!!'
                ]);
            }
        }else{
            return response()->json([
                'message' => 'error occurred while uploading image'
            ]);
        }
    }

    public function getProductByEmail($email){
        $products = (new Product)::where('email',$email)->get();
        return response()->json(['products' => $products]);
    }

    public function createOffer(Request $request){
        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'price' => $request->get('price'),
            'productId' => $request->get('productId'),
            'sellerEmail' => $request->get('sellerEmail')
        ];
        $result = (new Offer)::create($data);
        if($result){
            return response()->json([
                'message'=>'Offer Created Successfully!!'
            ]);
        }else{
            return response()->json([
                'message'=>'Error Occurred!!'
            ]);
        }
    }

    public function update($id, Request $request){
        $product = (new Product)::where('id',$id)->get();
        $result = $product[0]->getOriginal();
        $name = $request->get('name');
        $description = $request->get('description');
        if($request->get('image') == ''){
            $image = $result['image'];
        }else{
            $file = $request->file('image');
            $file->move('../oldcoin/public/uploads/',$file->getClientOriginalName());
            $image = $file->getClientOriginalName();
        }
        $result = (new Product)::where('id',$id)->update(['name'=>$name,'description'=>$description,'image' => $image]);
        if($result){
            return response()->json([
                'message'=>'Product Updated Successfully!!'
            ]);
        }else{
            return response()->json([
                'message'=>'Error Occurred!!'
            ]);
        }
    }
}
