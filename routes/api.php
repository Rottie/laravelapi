<?php


//Default import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Import AuthController,UserController,Model User
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



//Sign Out 
Route::post('/auth/signup', [AuthController::class, 'signup']);
//Sign In
Route::post('/auth/signin', [AuthController::class, 'signin']);
//Sign Out
Route::post('/auth/signout', [AuthController::class, 'signout']);

//Update User
Route::put('/auth/users/{id}', [UserController::class, 'update']);
//Get user
Route::get('/auth/users/{id}', [UserController::class, 'getUser']);



