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

// ******************** //
//     Admin Routes
// ******************** //

// Authentication Routes...
Route::get('admin/login', 'Auth\Admin\LoginController@login')->name('admin.auth.login');
Route::post('admin/login', 'Auth\Admin\LoginController@loginAdmin')->name('admin.auth.loginAdmin');
Route::any('admin/logout', 'Auth\Admin\LoginController@logout')->name('admin.auth.logout');

// Password Reset Routes...
Route::get('admin/forgot-password', 'Auth\Admin\ForgotPasswordController@forgotPasswordForm')->name('admin.auth.forgot-password');
Route::post('admin/send-reset-link-email', 'Auth\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.auth.send-reset-link-email');
Route::get('admin/reset-password/{token}', 'Auth\Admin\ForgotPasswordController@resetPasswordForm');
Route::post('admin/reset-password', 'Auth\Admin\ForgotPasswordController@resetPassword')->name('admin.auth.reset-password');

Route::group(['namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.check.status']], function () {
    Route::get('/', 'DashboardController@dashboard')->name('dashboard');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

    Route::get('settings', 'SettingController@index')->name('settings');
    Route::post('settings', 'SettingController@updateSettings')->name('update-settings');

    Route::get('profile', 'AdminController@profile')->name('profile');
    Route::post('profile', 'AdminController@updateProfile')->name('update-profile');

    Route::resource('sub-admins', 'AdminController');

    Route::get('investors/send-password/{id}', 'UserController@sendPassword');
    Route::get('investors/{id}/transactions', 'UserController@transactions');
    Route::get('investors/{id}/balances', 'UserController@balances');
    Route::get('investors/{id}/documents', 'UserController@documents');
    Route::post('investors/verify-documents', 'UserController@verifyDocuments');
    Route::resource('investors', 'UserController');

    Route::resource('roles', 'RoleController');
    Route::resource('email-templates', 'EmailTemplateController');

    Route::resource('pools', 'PoolController');
    
    Route::get('deposits/{id}/approve', 'DepositController@approve');
    Route::post('deposits/download-csv', 'DepositController@downloadCsv');
    Route::resource('deposits', 'DepositController');

    Route::get('withdraws/{id}/approve', 'WithdrawController@approve');
    Route::post('withdraws/download-csv', 'WithdrawController@downloadCsv');
    Route::resource('withdraws', 'WithdrawController');
});

// ******************* //
//    Frontend Routes
// ******************* //

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('forgot-password', 'Auth\ForgotPasswordController@forgotPasswordForm')->name('auth.forgot-password');
Route::post('send-reset-link-email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.send-reset-link-email');
Route::get('reset-password/{token}', 'Auth\ForgotPasswordController@resetPasswordForm');
Route::post('reset-password', 'Auth\ForgotPasswordController@resetPassword')->name('auth.reset-password');

Route::get('/', 'AppController@index');

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {

    // *************************** //
    //     Dashboard Routes
    // *************************** //

    Route::group(['namespace' => 'Dashboard', 'as' => 'dashboard.', 'middleware'=> ['auth','user.check.status']], function () {
        
    });
});

// ******************* //
//     Test Routes
// ******************* //

Route::get('test', 'TestController@index');
