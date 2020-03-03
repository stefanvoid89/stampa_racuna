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


Route::get('/warranty', 'WarrantyController@index');
Route::get('/warranty/create', 'WarrantyController@create_edit');
Route::get('/warranty/edit/{id}', 'WarrantyController@create_edit');


Route::get('/print', 'PrintController@index');

Route::get('/print/print/{id}', 'PrintController@print');
