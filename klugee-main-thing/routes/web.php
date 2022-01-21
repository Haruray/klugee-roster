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
Route::get('/attendance/history','MainController@AttendanceHistory');
Route::get('/attendance/{attendance_id}','MainController@AttendanceView');
Route::get('/students','MainController@Students');
Route::get('/students/{student_id}','MainController@StudentsData');
Route::get('/students/{student_id}/attendance-history','MainController@StudentsAttendanceHistory');
Route::get('/students/{student_id}/progress-report/{program}','MainController@StudentsProgressReport');


Route::post('/attendance/input-process','MainController@AttendanceInputProcess');
Route::post('/attendance/edit','MainController@AttendanceEdit');
Route::post('/attendance/progress-report/input-process','MainController@ProgressReportInputProcess');

Route::get('/get/student','RequestController@GetStudentData');
Route::get('/get/attendance/{id}','RequestController@GetAttendanceData');
