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
    return view('welcome');
});

Auth::routes();

Route::get('/', 'MainController@index')->name('home');
Route::get('/attendance', 'MainController@AttendanceMenu');
Route::get('/attendance/input','MainController@AttendanceInput');
Route::get('attendance/progress-report/{attendance_id}','MainController@AttendanceProgressReport');

Route::post('/attendance/input-process','MainController@AttendanceInputProcess');
Route::post('/attendance/progress-report/input-process','MainController@ProgressReportInputProcess');

Route::get('/get/student','RequestController@GetStudentData');
Route::get('/get/attendance/{id}','RequestController@GetAttendanceData');