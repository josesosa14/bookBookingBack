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
    return view('welcome');
});

//AUTH
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('register', 'Auth\RegisterController@register');
//USER
Route::put('updateProfile/{id}', 'UsersController@update')->middleware('auth');
Route::put('changePassword','UsersController@changePassword')->middleware('auth');
//BOOK
Route::resource('author','AuthorController')->middleware('auth');
Route::resource('book','BookController')->middleware('auth');

Route::post('booking','BookController@booking')->middleware('auth');
Route::post('cancelBooking','BookController@cancelBooking')->middleware('auth');
Route::get('userBooks','UsersController@showUserBooks')->middleware('auth');



// Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
