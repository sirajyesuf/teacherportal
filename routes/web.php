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
Route::get('/users', 'UserController@getlist')->name('user.getlist');
Route::post('/user/delete', 'UserController@delete')->name('user.delete');
Route::get('/getTrainerName', 'UserController@getTrainerName')->name('trainer.name');

Route::get('/student/create', 'StudentController@create')->name('student.create');
Route::post('/student/add', 'StudentController@add')->name('student.add');
Route::post('/student/date/update', 'StudentController@dateUpdate')->name('appointment.update');
Route::post('/student/date/check', 'StudentController@dateCheck')->name('appointment.check');
Route::get('/student/profile/{student}', 'StudentController@profile')->name('student.profile');
Route::post('/student/description/update', 'StudentController@descriptionUpdate')->name('student.description.update');
Route::get('/student/past', 'StudentController@pastStudent')->name('student.past');
Route::post('/student/past', 'StudentController@pastStudent')->name('student.past.post');
Route::post('/student/delete', 'StudentController@delete')->name('student.delete');
Route::post('/student/name/update', 'StudentController@nameUpdate')->name('student.nameUpdate');


Route::get('/lesson/{id}', 'LessonController@index')->name('lesson');
Route::post('/lesson', 'LessonController@index')->name('lesson.post');

Route::get('/lesson-bt/{id}', 'LessonController@btIndex')->name('lesson-bt');
Route::post('/lesson-bt', 'LessonController@btIndex')->name('lesson-bt.post');

Route::get('/lesson-im/{id}', 'LessonController@imIndex')->name('lesson-im');
Route::post('/lesson-im', 'LessonController@imIndex')->name('lesson-im.post');

Route::get('/lesson-sand/{id}', 'LessonController@sandIndex')->name('lesson-sand');
Route::post('/lesson-sand', 'LessonController@sandIndex')->name('lesson-sand.post');

Route::get('/select/template/{student}', 'LessonController@templateChoice')->name('select.template');
Route::post('/lessons/create', 'LessonController@create')->name('lesson.create');

Route::post('/lessons/update', 'LessonController@update')->name('lesson.update');
Route::post('/lessons-bt/update', 'LessonController@btUpdate')->name('lesson-bt.update');
Route::post('/lessons-im/update', 'LessonController@imUpdate')->name('lesson-im.update');
Route::post('/lessons-sand/update', 'LessonController@sandUpdate')->name('lesson-sand.update');

Route::post('/lessons/delete', 'LessonController@delete')->name('lesson.delete');

Route::post('/lesson/addSift', 'LessonController@addSift')->name('lesson.addSift');
Route::post('/lesson/addBtLang', 'LessonController@addBtLang')->name('lesson.addBtLang');
Route::post('/lesson/addIm', 'LessonController@addIm')->name('lesson.addIm');
Route::post('/lesson/addSand', 'LessonController@addSand')->name('lesson.addSand');

Route::get('/casenotes/{id}', 'CaseNoteController@index')->name('casenotes');
Route::post('/case-note/update', 'CaseNoteController@update')->name('casenote.update');
Route::post('/case-note/addCmm', 'CaseNoteController@addCmm')->name('casenote.addCmm');
Route::post('/case-note/addPrs', 'CaseNoteController@addPrs')->name('casenote.addPrs');
Route::post('/case-note/addCom', 'CaseNoteController@addCom')->name('casenote.addCom');

Route::post('/case-note/update/cmm', 'CaseNoteController@updateCmm')->name('casenote.updateCmm');
Route::post('/case-note/update/prs', 'CaseNoteController@updatePrs')->name('casenote.updatePrs');
Route::post('/case-note/update/com', 'CaseNoteController@updateCom')->name('casenote.updateCom');

Route::post('/case-note/delete/cmm', 'CaseNoteController@deleteCmm')->name('casenote.deleteCmm');
Route::post('/case-note/delete/prs', 'CaseNoteController@deletePrs')->name('casenote.deletePrs');
Route::post('/case-note/delete/com', 'CaseNoteController@deleteCom')->name('casenote.deleteCom');

Route::post('/lesson-hours/add', 'LessonLogController@add')->name('log.hours.add');
Route::post('/lesson-hours/update', 'LessonLogController@update')->name('log.hours.update');
Route::get('/lesson-hours/details', 'LessonLogController@details')->name('logHour.details');
Route::post('/lesson-hours/delete', 'LessonLogController@delete')->name('logHour.delete');


Route::post('/log-hours/add', 'LogHourController@add')->name('lesson.hours.add');
Route::post('/log-hours/update', 'LogHourController@update')->name('lesson.hours.update');
Route::get('/log-hours/details', 'LogHourController@details')->name('lesson.hours.details');
Route::post('/log-hours/delete', 'LogHourController@delete')->name('lesson.hours.delete');

Route::post('/tls/add', 'TlsController@add')->name('tls.add');
Route::get('/tls/details', 'TlsController@details')->name('tls.details');
Route::post('/tls/update', 'TlsController@update')->name('tls.update');
Route::post('/tls/delete', 'TlsController@delete')->name('tls.delete');
Route::post('/tls/delete-multiple', 'TlsController@deleteMultiple')->name('tls.delete.multiple');
Route::post('/tls/multiAdd', 'TlsController@multiAdd')->name('tls.multiAdd');

Route::get('/notification/read', 'NotificationController@readNotification')->name('notification.read');

Route::get('/test', 'CaseNoteController@test')->name('test');




