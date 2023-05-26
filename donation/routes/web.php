<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MpesaController;


Route::get('/', function () {
    return view('welcome');
});

Route::controller(PaymentController::class)
->prefix('payment')
->as('payment')
->group(function(){
    Route::get('/','index')->name('index');
    Route::get('/token','token')->name('token');
    Route::get('/initiatePush','initiateStkPush')->name('initiatePush');
    Route::post('/stkCallBack','stkCallback')->name('stkCallBack');

});

