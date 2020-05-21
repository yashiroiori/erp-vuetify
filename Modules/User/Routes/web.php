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

Route::prefix('admin/system')->middleware('auth','web')->group(function() {
    // User routes
    Route::get('user','UserController@index')->name('user.index');
    Route::get('user/create','UserController@create')->name('user.create');
    Route::post('user','UserController@store')->name('user.store');
    Route::get('user/{user}/edit','UserController@edit')->name('user.edit');
    Route::put('user/{user}','UserController@update')->name('user.update');
    Route::get('user/{user}/show','UserController@show')->name('user.show');

    Route::patch('user/{user}/restore','UserController@restore')->name('user.restore');
    Route::post('user/{user}/delete','UserController@destroy')->name('user.destroy');
    Route::post('user/{user}/archive','UserController@archive')->name('user.archive');
    Route::get('user/list','UserController@list')->name('user.list');

    Route::post('user/batch/action','UserController@batchAction')->name('user.batch_action');
});
