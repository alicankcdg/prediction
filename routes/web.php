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


Route::get('/save-values', "HomeController@saveValues");
Route::get('/', function () {
    return redirect("/home");
});
Route::get('/stock/buy/', "HomeController@stocks");

//kullanıcının sahip olacağı maximum hisse senedi miktarını değiştirir windowsta redis sıkıntılı olduğu için relational db üzerinden yaptım
Route::get('/stock/count/{def}', "HomeController@setStockDefCount");

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/stocks', 'HomeController@index')->name('home');
Route::get('/stock/buy/{stockId}', 'HomeController@buyStock')->name('home');
Route::get('/stock/sell/{stockId}', 'HomeController@sellStock')->name('home');
