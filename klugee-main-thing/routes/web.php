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

//teacher route
Route::get('/', 'MainController@index')->name('home');
Route::get('/attendance', 'TeacherController@AttendanceMenu');
Route::get('/attendance/input','MainController@AttendanceInput');
Route::get('/attendance/teacher-input','AdminController@TeacherAttendanceInput');
Route::get('attendance/progress-report/{attendance_id}','MainController@AttendanceProgressReport');
Route::get('/attendance/history','MainController@AttendanceHistory');
Route::get('/attendance/{attendance_id}','MainController@AttendanceView');
Route::get('/students','MainController@Students');
Route::get('/students/{student_id}','MainController@StudentsData');
Route::get('/students/{student_id}/attendance-history','MainController@StudentsAttendanceHistory');
Route::get('/students/{student_id}/progress-report/{program}','MainController@StudentsProgressReport');
Route::get('/profile','MainController@CurrentUserProfile');
Route::get('/profile/students','MainController@CurrentUserStudents');
Route::get('/profile/attendance','MainController@CurrentUserAttendance');
Route::get('/schedule','MainController@Schedule');
Route::get('/earnings','MainController@Earnings');
//admin route
Route::get('/attendance-admin','AdminController@AttendanceAdmin');
Route::get('/user-profiles','AdminController@UserProfiles');
Route::get('/users','AdminController@UserList');
Route::get('/users/{user_id}','AdminController@UserSelectProfile');
Route::get('/users/{user_id}/students','AdminController@UserSelectStudents');
Route::get('/users/{user_id}/attendance','AdminController@UserSelectAttendance');
Route::get('/users/{user_id}/schedule','AdminController@UserSelectSchedule');
//
Route::get('/schedule-admin','AdminController@ScheduleAdmin');
Route::get('/accounting','AdminController@Accounting');
Route::get('/accounting/input-transaction','AdminController@InputTransaction');
Route::get('/accounting/input-transaction/income','AdminController@IncomeInputTransaction');
Route::get('/accounting/input-transaction/expense','AdminController@ExpenseInputTransaction');
Route::get('/accounting/financial-data','AdminController@FinancialData');
Route::get('/accounting/financial-data/report/{month}/{year}','AdminController@FinancialReport');

Route::get('/user-attendances','AdminController@UserAttendances');
Route::get('/user-attendances/approve/{id}','AdminController@UserAttendanceApproval');
Route::get('/schedule-admin/manage','AdminController@ScheduleAdminManage');
//teacher post route
Route::post('/attendance/input-process','MainController@AttendanceInputProcess');
Route::post('/attendance/edit','MainController@AttendanceEdit');
Route::post('/attendance/progress-report/input-process','MainController@ProgressReportInputProcess');
Route::post('/profile/upload','MainController@ProfilePictureChange');
Route::post('/profile/select/upload','AdminController@UserSelectProfilePictureChange');
//admin post route
Route::post('/schedule-admin/manage/add','AdminController@ScheduleAdd');
Route::post('/schedule-admin/manage/edit','AdminController@ScheduleEdit');
Route::post('/accounting/input-transaction/income/process','AdminController@IncomeProcess');

Route::get('schedule-admin/manage/delete/{schedule_id}','AdminController@ScheduleDelete');

//requests route
Route::get('/get/student','RequestController@GetStudentData');
Route::get('/get/attendance/{id}','RequestController@GetAttendanceData');
Route::get('/get/progress-report/{attendance_id}/documentation','RequestController@GetDocumentation');
Route::get('/get/schedule/{teacher_id}','RequestController@GetSchedule');
Route::get('/get/schedule/id/{schedule_id}','RequestController@GetScheduleWithId');
