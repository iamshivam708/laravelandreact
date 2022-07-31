<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;

class UserController extends Controller
{
    public function signup(Request $request){
        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'phone' => $request->get('phone')
        ];
        $result = (new User)::create($data);
        if($result){
            return response()->json([
                'message'=>'User Created Successfully!!'
            ]);
        }else{
            return response()->json([
                'message'=>'Error Occurred!!'
            ]);
        }
    }

    public function login(Request $request){
        $email = $request->get('email');
        $password = $request->get('password');
        $result = (new User)::where('email',$email)->where('password',$password)->get()->count();
        if($result == 1){
            return response()->json(['success' => 'Login Successfully']);
        }else{
            return response()->json([
                'message'=>'No User Found'
            ]);
        }
    }

}
