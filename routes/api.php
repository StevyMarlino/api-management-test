<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperationController;
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

Route::middleware('guest')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'createUser']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Verification User route
    Route::get('/user/verify/{token}', [AuthController::class, 'verifyUser'])->name('token.verify');
});


// route User and Admin
Route::group(['middleware' => ['auth:sanctum','role:admin,user']], function () {
    Route::prefix('operation')->group(function () {
        Route::post('addIncome',[OperationController::class,'addIncome']);
        Route::post('addExpense',[OperationController::class,'addExpense']);
        Route::get('listMyOperations',[OperationController::class,'myOperations']);
    });
});


// Route Admin
Route::group(['middleware' => ['auth:sanctum','role:admin']], function () {

    Route::get('listUsers',[OperationController::class,'listUsers']);
    Route::post('activeOrDesactive',[OperationController::class,'activeOrDesactive']);
    Route::post('addCategory',[OperationController::class,'addCategory']);
    Route::get('listCategory',[OperationController::class,'listCategory']);
    Route::get('listUsersOperations/{user_id}',[OperationController::class,'listUsersOperations']);

    Route::prefix('operation')->group(function () {
        Route::get('listAllOperations',[OperationController::class,'allOperation']);
    });
});
