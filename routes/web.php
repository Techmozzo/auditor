<?php

use App\Http\Controllers\AuditorController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\SetupController;
use \App\Http\Controllers\ValidationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConfirmationRequestController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;

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

// Route::group(['middleware' => 'https'], function () {

Route::get('/', [LoginController::class, 'showLoginForm']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/setup', [SetupController::class, 'index'])->name('setup');

Route::match(['get', 'post'], 'confirmation-requests/{id}/signatories/{signatory}', [ConfirmationRequestController::class, 'viewRequest'])->name('request.view');
Route::match(['get', 'post'], 'confirmation-requests/{id}/signatories/{signatory}/sign', [ConfirmationRequestController::class, 'signRequest'])->name('request.sign');

Route::group(['middleware' => ['auth', 'block']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::get('/{id}/email-verification', [AuditorController::class, 'verification'])->name('email.verification');

Route::group(['middleware' => ['auth', 'block', 'company.verify']], function () {
    Route::resource('confirmation-requests', ConfirmationRequestController::class);

    Route::post('/validation/upload', [ValidationController::class, 'upload'])->name('validation.upload');
    Route::get('/validation', [ValidationController::class, 'index'])->name('validation.index');

    Route::post('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [SettingController::class, 'profile'])->name('profile.index');

    // generate otp
    Route::get('/generate-otp/{user_id?}', OtpController::class);
});

Route::group(['middleware' => ['auth', 'auditor', 'block', 'company.verify']], function () {
    // Help Center
    Route::match(['get', 'post'], '/help-center', HelpCenterController::class)->name('help-center');
});

Route::group(['middleware' => ['auth', 'admin', 'block']], function () {

    Route::get('/auditors/{id}/verification', [AuditorController::class, 'verification'])->name('auditors.verification');
    Route::get('/auditors/{id}/delete', [AuditorController::class, 'destroy'])->name('auditors.delete');
    Route::get('/auditors/{id}/block', [AuditorController::class, 'block'])->name('auditors.block');

    Route::resource('auditors', AuditorController::class);
});
// });


Route::get('/test', function(){
    return view('layouts.otp_validation');
});
