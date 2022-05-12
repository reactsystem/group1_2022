<?php

use App\Http\Controllers\Admin\AdminTopPageController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Front\AttendanceController;
use App\Http\Controllers\Front\RequestController;
use App\Http\Controllers\Front\TopPageController;
use App\Http\Controllers\UserEditController;

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

    Route::get('/attend-manage', [AttendanceController::class, 'index'])->name('attend');

    Route::get('/attends', [AttendanceController::class, 'index'])->name('attend');
    Route::get('/attends/start', [AttendanceController::class, 'attend']);
    Route::get('/attends/end', [AttendanceController::class, 'leave']);
    Route::get('/attends/cancel', [AttendanceController::class, 'cancelLeft']);
    Route::post('/api/v1/attends/comment/set', [AttendanceController::class, 'saveWorkMemo']);

    Route::get('/request/create', [RequestController::class, 'createRequest']);
    Route::post('/request/create', [RequestController::class, 'checkRequest']);
    Route::get('/request/create/back', [RequestController::class, 'checkRequestBack']);
    Route::get('/request/{id}/cancel', [RequestController::class, 'cancelRequest']);
    Route::get('/request/{id}', [RequestController::class, 'show']);
    Route::get('/request', [RequestController::class, 'index'])->name('request');

    Route::get('/admin', [AdminTopPageController::class, 'index'])->name('admin-home');

    Route::get('/admin/request', [AdminRequestController::class, 'request']);
    Route::post('/admin/request', [AdminRequestController::class, 'search']);
    Route::get('/admin/request/create', [AdminRequestController::class, 'create']);
    Route::post('/admin/request/create', [AdminRequestController::class, 'check']);

    Route::get('/admin/request/detail', [AdminRequestController::class, 'detail']);
    Route::post('/admin/request/approve', [AdminRequestController::class, 'approve']);
    Route::post('/admin/request/reject', [AdminRequestController::class, 'reject']);


    

});



Route::get('/account',[UserEditController::class, 'account']);
Route::get('/account/edit',[UserEditController::class, 'account_edit']);
Route::post('/account/account_edit_done',[UserEditController::class, 'account_edit_done']);
Route::get('/account/password_update',[UserEditController::class, 'password_update']);
Route::patch('/account/password_update_done',[UserEditController::class, 'password_update_done']);






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
