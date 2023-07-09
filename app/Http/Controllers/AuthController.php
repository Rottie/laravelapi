<?php

//this class belongs to this namespace
namespace App\Http\Controllers;

// imports the Request class from this namespace,
// Purpose:Handle htpp request
use Illuminate\Http\Request;

// imports the Validator facade from  this namespace
// Purpose:provides convenient validation methods.
use Illuminate\Support\Facades\Validator;


//imports the User model from this namepsace
// Purpose: represents the user model in the application
use App\Models\User;

//imports the Str class from this namespace
//Purpose:providing useful string manipulation methods
use Illuminate\Support\Str;

// imports the Auth facade from this namespace
//Purpose:handle authentication operations.
use Illuminate\Support\Facades\Auth;

//For signIn auth
class AuthController extends Controller
{
    public function signup(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new user model instance, 
        $user = new User();
        //sets the user's name, email, and password,
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        // saves it to the database
        $user->save();

        // Return a success response
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function signin(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        

        // Attempt to authenticate the user
        // using the attempt method of the auth() helper function,
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

    // Get the authenticated user
    $user = auth()->user();
 
         // Generate a unique authToken value
         $authToken = Str::uuid()->toString();

    // Generate and return a JWT token with the authToken
    $token = auth()->user()->createToken($authToken)->accessToken;


       // Return the token, authToken, and user information
       return response()->json([
           'token' => $token,
           'authToken' => $authToken,
           'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ],
       ], 200);     
 
    }

    public function signout(Request $request)
    {
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Sign out successful']);
    }
}
