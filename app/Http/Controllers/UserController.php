<?php



//this class belongs to this namespace
namespace App\Http\Controllers;

//imports the User model from this namepsace
// Purpose: represents the user model in the application
use App\Models\User;

// imports the Request class from this namespace,
// Purpose:Handle htpp request
use Illuminate\Http\Request;


// imports the Validator facade from  this namespace
// Purpose:provides convenient validation methods.
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{


    //takes a request object and the user ID as parameters
    public function update(Request $request, $id)
    {
        // finds the user by the provided ID
        $user = User::find($id);
        
        //if not exist return user not found
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
     
        //return err msg for fail validation for req data
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Update the user's name, email, and password
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
    
        return response()->json(['message' => 'User updated successfully'], 200);
    }


    //takes a request object and the user ID as parameters

    public function getUser(Request $request,$id)
    {

      // finds the user by the provided ID
    $user = User::find($id);
 
  //if not exist return user not found
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

   //return user info
    return response()->json([
        'name' => $user->name,
        'email' => $user->email,
        'password' =>  $user->password,
    ], 200);

    }

}
