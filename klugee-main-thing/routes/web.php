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

//FIRST LEVEL ACCESS
//teacher route
Route::get('/', 'MainController@index')->name('home');
Route::get('/attendance', 'TeacherController@AttendanceMenu');
Route::get('/attendance/input','MainController@AttendanceInput');
Route::get('/attendance/teacher-input','AdminController@TeacherAttendanceInput');
Route::get('attendance/progress-report/{attendance_id}','MainController@AttendanceProgressReport');
Route::get('/attendance/history','MainController@AttendanceHistory');
Route::get('/attendance/{attendance_id}','MainController@AttendanceView');
Route::get('/attendance/progress-report/{attendance_id}/filled','MainController@ProgressView');
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
Route::get('/notification','MainController@Notification');
Route::get('/notification/mark-as-read/{notif_id}', 'MainController@MarkNotification');
Route::get('/notification/mark-all-as-read', 'MainController@MarkAllNotification');


//teacher post route
Route::post('/attendance/input-process','MainController@AttendanceInputProcess');
Route::post('/attendance/edit','MainController@AttendanceEdit');
Route::post('/attendance/progress-report/input-process','MainController@ProgressReportInputProcess');
Route::post('/profile/upload','MainController@ProfilePictureChange');
Route::post('/profile/edit','MainController@ProfileEdit');

//SECOND LEVEL ACCESS
//minimum head teacher route
Route::get('/attendance-admin','HeadTeacherController@AttendanceAdmin');
Route::get('/user-profiles','HeadTeacherController@UserProfiles');
Route::get('/users','HeadTeacherController@UserList');
Route::get('/users/{user_id}','HeadTeacherController@UserSelectProfile');
Route::get('/users/{user_id}/students','HeadTeacherController@UserSelectStudents');
Route::get('/users/{user_id}/attendance','HeadTeacherController@UserSelectAttendance');
Route::get('/users/{user_id}/schedule','HeadTeacherController@UserSelectSchedule');

Route::get('/schedule-admin','HeadTeacherController@ScheduleAdmin');
Route::get('/schedule-admin/detailed/{teacher}','HeadTeacherController@ScheduleAll');


Route::get('/user-attendances','HeadTeacherController@UserAttendances');
Route::get('/user-attendances/approve/{id}','HeadTeacherController@UserAttendanceApproval');
Route::get('/user-attendances/delete/{id}','HeadTeacherController@UserAttendanceDelete');
Route::get('/schedule-admin/manage','HeadTeacherController@ScheduleAdminManage');

Route::get('/students/{student_id}/progress-report/{program}/generate','HeadTeacherController@ReportGenerate');
Route::get('schedule-admin/manage/delete/{schedule_id}','HeadTeacherController@ScheduleDelete');

//second level access post route
Route::post('/schedule-admin/manage/add','HeadTeacherController@ScheduleAdd');
Route::post('/schedule-admin/manage/edit','HeadTeacherController@ScheduleEdit');
Route::post('/profile/select/upload','HeadTeacherController@UserSelectProfilePictureChange');
Route::post('/generate-report','HeadTeacherController@ReportGenerateProcess');



//THIRD LEVEL ACCESS
//minimum admin route
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
Route::get('/new-student','AdminController@NewStudent');

Route::post('/accounting/input-transaction/income/process','AdminController@IncomeProcess');
Route::post('/accounting/input-transaction/expense/process','AdminController@ExpenseProcess');
Route::post('/accounting/spp/process','AdminController@SPPProcess');


//FOURTH LEVEL ACCESS
//minimum superadmin route
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
Route::get('/accounting/teacher-payment','SuperAdminController@TeacherPayment');
Route::get('/accounting/teacher-payment/salary','SuperAdminController@TeacherSalary');
Route::get('/accounting/teacher-payment/incentive','SuperAdminController@TeacherIncentive');


Route::get('/new-user','SuperAdminController@AddTeacherMenu');



//super admin post route
Route::post('/management/fee-edit','SuperAdminController@FeeEdit');
Route::post('/management/incentive-edit','SuperAdminController@IncentiveEdit');
Route::post('/management/salary-edit','SuperAdminController@SalaryEdit');
Route::post('/management/fee-add','SuperAdminController@FeeAdd');
Route::post('/management/program-add','SuperAdminController@ProgramAdd');
Route::post('/accounting/teacher-payment/salary/process','SuperAdminController@TeacherSalaryProcess');
Route::post('/accounting/teacher-payment/incentive/process','SuperAdminController@TeacherIncentiveProcess');


Route::post('/users/add/process','SuperAdminController@AddTeacherProcess');




//requests route
Route::get('/get/student','RequestController@GetStudentData');
Route::get('/get/attendance/{id}','RequestController@GetAttendanceData');
Route::get('/get/progress-report/{attendance_id}/documentation','RequestController@GetDocumentation');
Route::get('/get/schedule/{teacher_id}','RequestController@GetSchedule');
Route::get('/get/schedule/id/{schedule_id}','RequestController@GetScheduleWithId');
Route::get('/get/parent-partner','RequestController@GetParentPartner');

Route::get('/nota','AdminController@TesNota');
Route::get('/report','AdminController@Report');
