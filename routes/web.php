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


Route::get('/', 'HomeController@index');

Route::get('/warranty', 'WarrantyController@index');
Route::get('/warranty/create', 'WarrantyController@create_edit');
Route::get('/warranty/edit/{id}', 'WarrantyController@create_edit');
Route::get('/warranty/print/{id}', 'WarrantyController@print_warranty');

Route::get('/print', 'PrintController@index');

Route::get('/printEUR/print/{id}', 'PrintController@printEUR');
Route::get('/print/print/{id}', 'PrintController@print');


Route::get('/crm', 'CrmController@index');

Route::get('/test', 'PrintController@test');
Route::get('/test1', 'HomeController@generateXML');
