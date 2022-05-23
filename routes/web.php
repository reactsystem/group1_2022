<?php

use App\Http\Controllers\Admin\AdminAttendManagementController;
use App\Http\Controllers\Admin\AdminAttendsController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTopPageController;
use App\Http\Controllers\Front\AttendanceController;
use App\Http\Controllers\Front\AttendanceManagementController;
use App\Http\Controllers\Front\NotificationController;
use App\Http\Controllers\Front\RequestController;
use App\Http\Controllers\Front\TopPageController;
use App\Http\Controllers\Front\UserEditController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
    return redirect('/login');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [TopPageController::class, 'index'])->name('home');

    Route::get('/notification/{id}', [NotificationController::class, 'jump']);

    /* 出勤・退勤 */
    Route::get('/attends', [AttendanceController::class, 'index'])->name('attend');
    Route::get('/attends/start', [AttendanceController::class, 'attend']);
    Route::get('/attends/end', [AttendanceController::class, 'leave']);
    Route::get('/attends/cancel', [AttendanceController::class, 'cancelLeft']);
    Route::post('/api/v1/attends/comment/set', [AttendanceController::class, 'saveWorkMemo']);

    /* 勤怠情報確認 */
    Route::get('/attend-manage/confirm', [AttendanceManagementController::class, 'confirmReport']);
    Route::get('/attend-manage/unconfirm', [AttendanceManagementController::class, 'unconfirmReport']);
    Route::get('/attend-manage', [AttendanceManagementController::class, 'index'])->name('attend');

    /* 各種申請 */
    Route::get('/request/create', [RequestController::class, 'createRequest']);
    Route::post('/request/create', [RequestController::class, 'checkRequest']);
    Route::get('/request/create/back', [RequestController::class, 'checkRequestBack']);
    Route::get('/request/{id}/cancel', [RequestController::class, 'cancelRequest']);
    Route::get('/request/{id}', [RequestController::class, 'show']);
    Route::get('/request', [RequestController::class, 'index'])->name('request');

    /* ユーザー管理 */
    Route::get('/account', [UserEditController::class, 'account']);
    Route::get('/account/edit', [UserEditController::class, 'account_edit']);
    Route::get('/account/holidays', [UserEditController::class, 'getHolidays']);
    Route::get('/account/notifications', [UserEditController::class, 'notifications']);
    Route::get('/account/notifications/{id}', [UserEditController::class, 'viewNotification']);
    Route::get('/account/notifications/delete/{id}', [UserEditController::class, 'deleteNotification']);
    Route::post('/account/account_edit_done', [UserEditController::class, 'account_edit_done']);
    Route::get('/account/password_update', [UserEditController::class, 'password_update']);
    Route::patch('/account/password_update_done', [UserEditController::class, 'password_update_done']);


});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin', [AdminSettingsController::class, 'index'])->name('admin-home');

    /* 社員情報管理 */
    Route::get('/admin/attends', [AdminAttendsController::class, 'admin_attends']);
    Route::post('/admin/attends', [AdminAttendsController::class, 'admin_search']);

    Route::get('/admin/attends/new', [AdminAttendsController::class, 'admin_new']);
    Route::get('/admin/attends/view', [AdminAttendsController::class, 'admin_view']);
    Route::get('/admin/attends/edit', [AdminAttendsController::class, 'admin_edit']);
    Route::post('/admin/attends/add', [AdminAttendsController::class, 'add_new_user']);
    Route::post('/admin/attends/update', [AdminAttendsController::class, 'update_user']);
    Route::get('/admin/attends/notify', [AdminAttendsController::class, 'message']);
    Route::post('/admin/attends/notify', [AdminAttendsController::class, 'createMessage']);

    Route::get('/admin/attends/holidays/{user_id}', [AdminAttendsController::class, 'viewHolidaysGet']);
    Route::get('/admin/attends/holidays/{user_id}/edit/{id}', [AdminAttendsController::class, 'editHolidayGet']);
    Route::get('/admin/attends/holidays/{user_id}/new', [AdminAttendsController::class, 'createHolidayGet']);
    Route::post('/admin/attends/holidays/{user_id}/edit/{id}', [AdminAttendsController::class, 'editHolidayPost']);
    Route::post('/admin/attends/holidays/{user_id}/new', [AdminAttendsController::class, 'createHolidayPost']);

    /* 勤怠情報管理 */
    Route::get('/admin/attend-manage/search', [AdminAttendManagementController::class, 'search']);
    Route::get('/admin/attend-manage/view/{id}', [AdminAttendManagementController::class, 'view']);
    Route::get('/admin/attend-manage/edit/{id}', [AdminAttendManagementController::class, 'edit']);
    Route::get('/admin/attend-manage/new', [AdminAttendManagementController::class, 'new']);
    Route::post('/admin/attend-manage/new', [AdminAttendManagementController::class, 'createData']);
    Route::get('/admin/attend-manage/delete/{id}', [AdminAttendManagementController::class, 'deleteData']);
    Route::post('/admin/attend-manage/edit/{id}', [AdminAttendManagementController::class, 'editData']);
    Route::get('/admin/attend-manage/calender/{id}', [AdminAttendManagementController::class, 'showUserCalender']);
    Route::get('/admin/attend-manage/confirm', [AdminAttendManagementController::class, 'approveReport']);
    Route::get('/admin/attend-manage/cancel', [AdminAttendManagementController::class, 'unapproveReport']);
    Route::get('/admin/attend-manage/download/{user_id}/{year}/{month}', [AdminAttendManagementController::class, 'exportDataCsv']);
    Route::get('/admin/attend-manage/download-requests/{user_id}/{year}/{month}', [AdminAttendManagementController::class, 'exportRequestDataCsv']);
    Route::get('/admin/attend-manage', [AdminAttendManagementController::class, 'index']);

    /* システム設定 */
    Route::get('/admin/settings', [AdminSettingsController::class, 'index']);

    Route::get('/admin/settings/holiday', [AdminSettingsController::class, 'holiday']);
    Route::get('/admin/settings/holiday/edit/{id}', [AdminSettingsController::class, 'viewHoliday']);
    Route::post('/admin/settings/holiday/edit/{id}', [AdminSettingsController::class, 'editHoliday']);
    Route::get('/admin/settings/holiday/delete/{id}', [AdminSettingsController::class, 'deleteHoliday']);
    Route::get('/admin/settings/holiday/new', [AdminSettingsController::class, 'newHoliday']);
    Route::post('/admin/settings/holiday/new', [AdminSettingsController::class, 'createHoliday']);

    Route::get('/admin/settings/department', [AdminSettingsController::class, 'department']);
    Route::get('/admin/settings/department/edit/{id}', [AdminSettingsController::class, 'viewDepartment']);
    Route::post('/admin/settings/department/edit/{id}', [AdminSettingsController::class, 'editDepartment']);
    Route::get('/admin/settings/department/delete/{id}', [AdminSettingsController::class, 'deleteDepartment']);
    Route::get('/admin/settings/department/new', [AdminSettingsController::class, 'newDepartment']);
    Route::post('/admin/settings/department/new', [AdminSettingsController::class, 'createDepartment']);

    Route::get('/admin/settings/request-types', [AdminSettingsController::class, 'requestTypes']);
    Route::get('/admin/settings/request-types/edit/{id}', [AdminSettingsController::class, 'viewRequestType']);
    Route::post('/admin/settings/request-types/edit/{id}', [AdminSettingsController::class, 'editRequestType']);
    Route::get('/admin/settings/request-types/delete/{id}', [AdminSettingsController::class, 'deleteRequestType']);
    Route::get('/admin/settings/request-types/new', [AdminSettingsController::class, 'newRequestType']);
    Route::post('/admin/settings/request-types/new', [AdminSettingsController::class, 'createRequestType']);

    Route::get('/admin/settings/general', [AdminSettingsController::class, 'general']);
    Route::get('/admin/settings/general/edit', [AdminSettingsController::class, 'editGeneral']);
    Route::post('/admin/settings/general/edit', [AdminSettingsController::class, 'updateGeneral']);
    Route::get('/admin/settings/general/download', [AdminSettingsController::class, 'downloadDefaultCsv']);

    Route::get('/admin/settings/notifications', [AdminSettingsController::class, 'notifications']);
    Route::get('/admin/settings/notifications/{id}', [AdminSettingsController::class, 'viewNotification']);
    Route::get('/admin/settings/notifications/delete/{id}', [AdminSettingsController::class, 'deleteNotification']);

    /* 各種申請 */
    Route::get('/admin/request', [AdminRequestController::class, 'request']);
    Route::post('/admin/request', [AdminRequestController::class, 'search']);
    Route::get('/admin/request/create', [AdminRequestController::class, 'create']);
    Route::post('/admin/request/create', [AdminRequestController::class, 'check']);
    Route::get('/admin/request/detail', [AdminRequestController::class, 'detail']);
    Route::post('/admin/request/approve', [AdminRequestController::class, 'approve']);
    Route::post('/admin/request/reject', [AdminRequestController::class, 'reject']);

    Route::get('/admin', [AdminTopPageController::class, 'index'])->name('admin-home');
});

Auth::routes(['register' => false]);

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? view('auth.passwords.success')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
