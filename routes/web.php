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
    return view('auth.login');
});

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@index')->name('home.post');

Route::get('/staff', 'StaffController@index')->name('staff');
Route::post('/staff', 'StaffController@index')->name('staff.post');

Route::get('/user/create', 'UserController@create')->name('user.create');
Route::post('/user/add', 'UserController@add')->name('user.add');
Route::get('/user/{user}/edit', 'UserController@edit')->name('user.edit');
Route::post('/user/{user}/update', 'UserController@update')->name('user.update');

Route::get('/student/create', 'StudentController@create')->name('student.create');
Route::post('/student/add', 'StudentController@add')->name('student.add');

