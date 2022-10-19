<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails()) {
            return response([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response([
            'message' => 'Register successfully',
            'data' => $user
        ]);

    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if($user) {
            if(Hash::check($request->password, $user->password)) {

                $token = $user->createToken(' auth token')->plainTextToken;

                return response([
                    'message' => 'Login successfully',
                    'token' => $token,
                    'data' => $user
                ], 400);
            }
            else {
                return response([
                    'message' => 'Incorrect credentials'
                ], 400);
            }
        }
        else {
            return response([
                'message' => 'Incorrect credentials'
            ], 400);
        }

    }


    public function user(Request $request) {
        return response([
            'message' => 'user successfully fatched',
            'data' => $request->user()
        ], 400);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return ['message' => 'user successfully logged out'];
    }

}
