<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\UserController;
use App\Notifications\PermitApproved;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//-------------------------------------------------------------------------------------
//API for selections in Admin blade view
//NOTE: API put above
//-------------------------------------------------------------------------------------
Route::match(['get', 'post'], '/admin/api/users', [UserController::class, 'usersAjax']);
Route::match(['get', 'post'], '/admin/api/holders', [UserController::class, 'holdersAjax']);


//-------------------------------------------------------------------------------------
//API for email notificaiton testing
//NOTE: API put above
//-------------------------------------------------------------------------------------
Route::get('/test-notification', function () {
    $user = User::find(25); // Replace with the actual user or recipient
    $user->notify(new PermitApproved);
    
    return "Notification sent!";
});

