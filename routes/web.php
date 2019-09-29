<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login_with_kite', 'HomeController@login_with_kite')->name('login_with_kite');
Route::get('/logout_kite', 'HomeController@logout_kite')->name('logout_kite');
