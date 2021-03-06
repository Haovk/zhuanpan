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
Route::any('/nineturntable/auth', 'Turntable\NineTurntableController@auth')->name('wxauth');;
Route::any('/nineturntable', 'Turntable\NineTurntableController@index')->name('thome');;
Route::any('/nineturntable/getitem', 'Turntable\NineTurntableController@getitem');
Route::any('/nineturntable/bindUser', 'Turntable\NineTurntableController@bindUser');
Route::any('/nineturntable/shareinfo', 'Turntable\NineTurntableController@shareinfo');
Route::any('/nineturntable/getTickets', 'Turntable\NineTurntableController@getTickets');
Route::any('/nineturntable/getAllTickets', 'Turntable\NineTurntableController@getAllTickets');