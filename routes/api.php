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
    Route::get('user/{id}', 'AuthController@loadUserWithId');   

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
    Route::get('getusersaccounts','AccountController@getUsersAccounts');
    Route::get('delete/{id}','AccountController@destroy');
    Route::Post('buyairtime','AccountController@buyAirtime');
    Route::Post('paybill','AccountController@PayBill');
    Route::Post('accountstatement','AccountController@accountStatement');
    Route::Post('deposittootheraccount','AccountController@depositOtherAccounts');
    Route::Post('withdrawfromotheraccount','AccountController@withdrawFromOtherAccount');

    /* OTP */
    Route::Post('getotp','OTPController@generateOtp');
    Route::Post('checkotp','OTPController@checkOTP');
    /* Borrow  */
    Route::Post('borrow','BorrowController@Borrow');
    Route::get('borrows','BorrowController@index'); 
    // Route::get('userloans','BorrowController@getUserLoans');
    Route::get('useractiveloan','BorrowController@getUserActiveLoan');
    Route::get('myborrows','BorrowController@myBorrows');
    Route::get('activeborrows','BorrowController@activeBorrows');

    /* Lend */
    Route::Post('lend','LendController@Lend');
    Route::Post('tranfercash','LendController@transferCash'); 
    Route::get('mylends','LendController@myLends'); 
    

    /* profile */
    Route::post('saveprofile','ProfileController@store');
    Route::get('show','ProfileController@show');
    Route::get('show/{id}','ProfileController@showprofile');

    /* Transactions */
    Route::get('trasactions','TransactionController@index');
    Route::post('/mytransaction','TransactionController@userTransactions');
    Route::post('/transact','StandingOrdersController@create');
    Route::get('/usertransactions','TransactionController@getUserTransaction');
    Route::post('/usertransaction','TransactionController@showUserTransactions');

    /* Schools */
    Route::get('/schools','SchoolController@index');
    Route::post('/addschool','SchoolController@store');
    Route::post('/editschool/{id}','SchoolController@update');

    /* Account types */
    Route::get('/accounttypes','AccountTypeController@index');
    Route::post('/postaccounttypes','AccountTypeController@store');
    Route::post('/updateaccounttypes/{id}','AccountTypeController@update');

    /* Reviews */
    Route::get('/all','ReviewController@getAll');
    Route::get('/reviews','ReviewController@index');
    Route::post('/sendreview','ReviewController@store');
    Route::post('/updatereview','ReviewController@update');

    /* Loan Types */
    Route::get('/loantypes','LoanTypeController@index');
    Route::post('/addloantype','LoanTypeController@store');
    Route::post('/updateloantype/{id}','LoanTypeController@update');
    Route::get('/deleteloantype/{id}','LoanTypeController@destroy');

    /* Loan */
    Route::get('/loans','LoanController@index');
    Route::get('/loan/{id}','LoanController@show');
    Route::post('/addloan','LoanController@store');
    Route::post('/updateloan/{id}','LoanController@update');
    Route::get('loanpayment/{id}','LoanController@loanStatement');
    Route::get('userloans/{id?}','LoanController@userLoans');
    Route::get('loans/{value}','LoanController@loanProcess');
    Route::get('/defaulting','LoanController@defaultingLoans');
    Route::get('/userloandetails','LoanController@getUserActiveLoanDetail');
    Route::Post('/payloan','LoanController@payLoan');

    /* Guarantors */
    Route::get('/guarantors','GuarantorController@index');
    Route::post('/guarantee','GuarantorController@store');
    Route::get('/loanguarantors/{id}','GuarantorController@getLoanGuarantors');

    /* Shares */
    Route::get('/shares','SharesController@index');
    Route::get('/usershares','SharesController@show');
    Route::post('/createshare','SharesController@store');
    Route::post('/depositshare','SharesController@deposit');

    /* Investment */
    Route::get('/investments','InvestController@index');
    Route::get('/myinvestments','InvestController@myInvestments');
    Route::get('/myactiveinvestments','InvestController@getUserActiveInvestmnets');
    Route::post('/invest','InvestController@store');
    Route::post('/requesttermination','InvestController@requestTermination'); 
    Route::post('/terminate','InvestController@terminate');
    Route::get('/myterminationrequests','InvestController@myTerminationRequests');

     /* Transaction costs */
     Route::get('/transactioncosts','TransactionCostController@index');
     Route::post('/addtransactioncost','TransactionCostController@store');
     Route::post('/updatetransactioncost/{id}','TransactionCostController@update');

     /* Standing orders */
     Route::get('/standingorders','StandingOrdersController@index');
     Route::get('/mystandingorders','StandingOrdersController@getMyStandingOrder');
     Route::post('/addstandingorder','StandingOrdersController@store');
     Route::post('/updatestandingorder/{id}','StandingOrdersController@update');
     Route::post('/stopstart','StandingOrdersController@stopStart');

     /* Group Account */
     Route::get('/groupaccounts','GroupAccountController@index');
     Route::post('/addgroupaccount','GroupAccountController@store');
     Route::post('/updatestandingorder/{id}','StandingOrdersController@update');
     Route::get('/usergroupaccounts','GroupAccountController@getuserGroupAccounts'); 
     Route::post('/getaccountbynumber','GroupAccountController@getAcountByAccountNumber');
     Route::post('/getgroupmembers','GroupAccountController@getGroupMembers');

    /* Reports */
    Route::get('usersreport','AccountController@usersreport');
  });


 
