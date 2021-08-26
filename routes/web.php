<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $pdf = url('titan.pdf');
    return view('welcome', ['pdf' => $pdf]);
});

route::post('/save_data' ,'MyController@index')->name('save_data');
route::get('/get_data' ,'MyController@get_data')->name('get_data');