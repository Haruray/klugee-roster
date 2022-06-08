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
Route::get('/management','MainController@Management');
//admin route
Route::get('/attendance-admin','AdminController@AttendanceAdmin');
Route::get('/user-profiles','AdminController@UserProfiles');
Route::get('/users','AdminController@UserList');
Route::get('/users/{user_id}','AdminController@UserSelectProfile');
Route::get('/users/{user_id}/students','AdminController@UserSelectStudents');
Route::get('/users/{user_id}/attendance','AdminController@UserSelectAttendance');
Route::get('/users/{user_id}/schedule','AdminController@UserSelectSchedule');

Route::get('/schedule-admin','AdminController@ScheduleAdmin');
Route::get('/accounting','AdminController@Accounting');
Route::get('/accounting/input-transaction','AdminController@InputTransaction');
Route::get('/accounting/input-transaction/income','AdminController@IncomeInputTransaction');
Route::get('/accounting/input-transaction/expense','AdminController@ExpenseInputTransaction');
Route::get('/accounting/financial-data','AdminController@FinancialData');
Route::get('/accounting/financial-data/report/{month}/{year}','AdminController@FinancialReport');
Route::get('/accounting/financial-data/recap','AdminController@FinancialRecap');
Route::get('/accounting/financial-data/recap/income/{month}/{year}','AdminController@FinancialRecapIncome');
Route::get('/accounting/financial-data/recap/expense/{month}/{year}','AdminController@FinancialRecapExpense');
Route::get('/accounting/spp','AdminController@SPP');
Route::get('/accounting/referral/{month}/{year}','AdminController@ReferralReport');

Route::get('/user-attendances','AdminController@UserAttendances');
Route::get('/user-attendances/approve/{id}','AdminController@UserAttendanceApproval');
Route::get('/schedule-admin/manage','AdminController@ScheduleAdminManage');

//Super Admin route
Route::get('/accounting/approvals','SuperAdminController@Approvals');
Route::get('/accounting/approvals/approve-fee/{fee_id}','SuperAdminController@FeeApproval');
Route::get('/accounting/approvals/delete-fee/{fee_id}','SuperAdminController@FeeDeletion');
Route::get('/accounting/approvals/approve-salary/{salary_id}','SuperAdminController@SalaryApproval');
Route::get('/accounting/approvals/delete-salary/{salary_id}','SuperAdminController@SalaryDeletion');
Route::get('/accounting/approvals/approve-incentive/{incentive_id}','SuperAdminController@IncentiveApproval');
Route::get('/accounting/approvals/delete-incentive/{incentive_id}','SuperAdminController@IncentiveDeletion');
Route::get('/accounting/approvals/approve-accounting/{accounting_id}','SuperAdminController@AccountingApproval');
Route::get('/accounting/approvals/delete-accounting/{accounting_id}','SuperAdminController@AccountingDeletion');
Route::get('/accounting/approvals/approve-referral/{referral_id}','SuperAdminController@ReferralApproval');
Route::get('/accounting/approvals/delete-referral/{referral_id}','SuperAdminController@ReferralDeletion');
Route::get('/accounting/approvals/approve-referral-front/{referral_id}','SuperAdminController@ReferralFrontApproval');
Route::get('/accounting/approvals/delete-referral-front/{referral_id}','SuperAdminController@ReferralFrontDeletion');
Route::get('/accounting/approvals/approve-referral-scheduling/{referral_id}','SuperAdminController@ReferralSchedulingApproval');
Route::get('/accounting/approvals/delete-referral-scheduling/{referral_id}','SuperAdminController@ReferralSchedulingDeletion');

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
Route::post('/accounting/input-transaction/expense/process','AdminController@ExpenseProcess');
Route::post('/accounting/spp/process','AdminController@SPPProcess');

Route::get('schedule-admin/manage/delete/{schedule_id}','AdminController@ScheduleDelete');

//requests route
Route::get('/get/student','RequestController@GetStudentData');
Route::get('/get/attendance/{id}','RequestController@GetAttendanceData');
Route::get('/get/progress-report/{attendance_id}/documentation','RequestController@GetDocumentation');
Route::get('/get/schedule/{teacher_id}','RequestController@GetSchedule');
Route::get('/get/schedule/id/{schedule_id}','RequestController@GetScheduleWithId');
