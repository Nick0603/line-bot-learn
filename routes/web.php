<?php

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
    return view('welcome');
});

Route::get('/line-notify-auth', 'LINENotifyController@auth')->name('line_notify_auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
