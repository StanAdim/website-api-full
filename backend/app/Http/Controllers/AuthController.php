<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(){
        $usersList = UserCollection::collection(User::all());
        if ($usersList !== []){
            return response()->json([
                'message'=> "Registered users",
                'data' => $usersList 
            ]);
        }
        else {
            return response()->json([
                'message'=> "No users!"
            ]);
        }

    }
    public function registerUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:2|max:255',
            'middleName' => 'max:255',
            'lastName' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:255',
            'confirm_password' => 'required|same:password',
        ]);
        #Failure response of Validation
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fails',
                'errors' => $validator->errors()
            ], 422);
        }
        #Creating New User 
        $newUser = User::create([
            'firstName' => $request->firstName,
            'lastName' =>  $request->lastName,
            'middleName' => $request->middleName,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'message' => 'User Registration Success',
            'data' => $newUser
        ], 200);      

   }
    public function loginUser(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        #Failure response of Validation
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fails',
                'errors' => $validator->errors()
            ], 422);
        }
        // Success on Validation
        // $user = User::with('role')->where('email', $request->email)->first();
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Login Successful!',
                    'token' => $token,
                    'data' => $user,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Incorrect Credentials',
                    'data' => ''
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Incorrect Credentials',
                'data' => ''
            ], 400);
        }
   }
}
