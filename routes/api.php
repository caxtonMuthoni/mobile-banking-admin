<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
   
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');    
    });
});

Route::group([
    'middleware' => 'auth:api'
  ], function() {

    /* User */
    Route::get('getusers', 'AuthController@getUsers');
    Route::get('userscount', 'AuthController@usersCount');
    Route::delete('delete/{id}', 'AuthController@destroy');
    Route::post('updateuser/{id}', 'AuthController@updateUser');

    /* Account */
    Route::get('balance','AccountController@checkBalance'); 
    Route::Post('stkpush','AccountController@STKPush'); 
    Route::Post('deposit','AccountController@deposit'); 
    Route::Post('withdraw','AccountController@withdraw');
    Route::Post('topupsap','AccountController@topUpSap'); 
    Route::Post('openaccount','AccountController@openAccount'); 

    /* OTP */
    Route::Post('getotp','OTPController@generateOtp');
    Route::Post('checkotp','OTPController@checkOTP');
    /* Borrow  */
    Route::Post('borrow','BorrowController@Borrow');
    Route::get('borrows','BorrowController@index'); 
    Route::get('userloans','BorrowController@getUserLoans');
    Route::get('useractiveloan','BorrowController@getUserActiveLoan');

    /* Lend */
    Route::Post('lend','LendController@Lend');
    Route::Post('tranfercash','LendController@transferCash'); 
  });