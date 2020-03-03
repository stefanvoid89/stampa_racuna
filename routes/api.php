<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/collect_subjects', 'PrintController@fetch_subjects');


Route::post('/warranty', 'WarrantyController@store_update');
Route::put('/warranty/{id}', 'WarrantyController@store_update');


Route::post('/fetch_icar_warranty_data', 'WarrantyController@fetch_data');
