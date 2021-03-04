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

Route::get('/print/print/{id}', 'PrintController@print');


Route::get('/open_wo', 'OpenWOController@index');
Route::get('/open_wo/export', 'OpenWOController@export');

Route::get('/regular_maint', 'RegularMaintController@index');
Route::get('/regular_maint/export', 'RegularMaintController@export');
Route::get('/regular_maint/export_detail', 'RegularMaintController@export_detail');

Route::get('/crm', 'CrmController@index');

Route::get('/generate-xml', 'PrintController@generateXML');
Route::get('/send-xml', 'PrintController@sendMailXML');


Route::get('/test', 'PrintController@test');
