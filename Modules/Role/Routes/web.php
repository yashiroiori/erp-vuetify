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
    // Role routes
    Route::get('role','RoleController@index')->name('role.index');
    Route::get('role/create','RoleController@create')->name('role.create');
    Route::post('role','RoleController@store')->name('role.store');
    Route::get('role/{role}/edit','RoleController@edit')->name('role.edit');
    Route::put('role/{role}','RoleController@update')->name('role.update');
    Route::get('role/{role}/show','RoleController@show')->name('role.show');

    Route::any('role/{role}/restore','RoleController@restore')->name('role.restore');
    Route::post('role/{role}/delete','RoleController@destroy')->name('role.destroy');
    Route::post('role/{role}/archive','RoleController@archive')->name('role.archive');
    Route::get('role/list','RoleController@list')->name('role.list');

    Route::post('role/batch/action','RoleController@batchAction')->name('role.batch_action');
});
