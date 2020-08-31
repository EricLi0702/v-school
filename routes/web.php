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

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Route::post('app/tag','TagController@addTag')->name('addTag');
// Route::get('app/tag','TagController@getTag')->name('getTag');
// Route::put('app/tag','TagController@editTag')->name('editTag');
// Route::delete('app/tag','TagController@deleteTag')->name('deleteTag');

Route::any('{slug}', function(){
    return view('welcome');
});