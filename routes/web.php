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
Route::post('/student/date/update', 'StudentController@dateUpdate')->name('appointment.update');
Route::get('/student/profile/{student}', 'StudentController@profile')->name('student.profile');
Route::post('/student/description/update', 'StudentController@descriptionUpdate')->name('student.description.update');

Route::get('/lesson/{id}', 'LessonController@index')->name('lesson');
Route::get('/select/template/{student}', 'LessonController@templateChoice')->name('select.template');
Route::post('/lessons/create', 'LessonController@create')->name('lesson.create');
Route::post('/lessons/update', 'LessonController@update')->name('lesson.update');

Route::get('/casenotes/{id}', 'CaseNoteController@index')->name('casenotes');
Route::post('/case-note/update', 'CaseNoteController@update')->name('casenote.update');

Route::post('/lesson-hours/add', 'LessonLogController@add')->name('log.hours.add');

Route::post('/log-hours/add', 'LogHourController@add')->name('lesson.hours.add');

Route::post('/tls/add', 'TlsController@add')->name('tls.add');




