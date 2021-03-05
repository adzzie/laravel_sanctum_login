<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/tokens/create', function (Request $request) {

    $validate = $request->validate([
        'email' => 'required | email',
        'password' => 'required'
    ]);

    if(!Auth::attempt($validate)){
        return response()->json(['message'=>'email atau password anda salah'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $token = Auth::user()->createToken($request->token_name);

    return response()->json(['token' => $token->plainTextToken]);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
            return $request->user();
        });

    Route::post('/tokens/delete', function (Request $request) {
        Log::debug(Auth::user());
        Auth::user()->currentAccessToken()->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    });
});
