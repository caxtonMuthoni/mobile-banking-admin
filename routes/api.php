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
    });
});

Route::group([
    'middleware' => 'auth:api'
  ], function() {

    /* User */
    Route::get('getusers', 'AuthController@getUsers');
    Route::get('userscount', 'AccountController@usersCount');
    Route::delete('delete/{id}', 'AuthController@destroy');
    Route::post('updateuser/{id}', 'AuthController@updateUser');
    Route::get('user', 'AuthController@loadUser');   

    /* Account */
    Route::get('accounts','AccountController@index'); 
    Route::get('balance','AccountController@checkBalance'); 
    Route::Post('stkpush','AccountController@STKPush'); 
    Route::Post('deposit','AccountController@deposit'); 
    Route::Post('withdraw','AccountController@withdraw');
    Route::Post('topupsap','AccountController@topUpSap'); 
    Route::Post('openaccount','AccountController@openAccount');
    Route::Post('directdeposit','AccountController@directDeposit');
    Route::Post('directwithdraw','AccountController@directWithdraw'); 

    /* OTP */
    Route::Post('getotp','OTPController@generateOtp');
    Route::Post('checkotp','OTPController@checkOTP');
    /* Borrow  */
    Route::Post('borrow','BorrowController@Borrow');
    Route::get('borrows','BorrowController@index'); 
    Route::get('userloans','BorrowController@getUserLoans');
    Route::get('useractiveloan','BorrowController@getUserActiveLoan');
    Route::get('myborrows','BorrowController@myBorrows');
    Route::get('activeborrows','BorrowController@activeBorrows');

    /* Lend */
    Route::Post('lend','LendController@Lend');
    Route::Post('tranfercash','LendController@transferCash'); 

    /* profile */
    Route::post('saveprofile','ProfileController@store');
    Route::get('show','ProfileController@show');

    /* Transactions */
    Route::get('trasactions','TransactionController@index');
    Route::post('/mytransaction','TransactionController@userTransactions');
  });

  Route::get('payment','AccountController@payment');