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

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

/*********************************************************************************
 * User Administration Routes
 *********************************************************************************/
Route::get('/admin/users', 'AdminController@listUsers');
Route::put('/admin/users', 'AdminController@createUser');
Route::delete('/admin/users', 'AdminController@deleteUsers');
Route::put('/admin/users/password', 'AdminController@changePassword');
Route::put('/admin/users/roles', 'AdminController@updateUserRoles');
Route::put('/admin/roles', 'AdminController@addRole');