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
Route::get('/artisan-call', function() {
    Artisan::call('storage:link');
	dd('storage link done.123');
});

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
    Route::get('investors/enable-login/{id}', 'UserController@enableLogin');
    Route::get('investors/{id}/referrals', 'UserController@referrals');
    Route::get('investors/{id}/transactions', 'UserController@transactions');
    Route::get('investors/{id}/password', 'UserController@password');
    Route::get('investors/{id}/balances', 'UserController@balances');
    Route::get('investors/{id}/documents', 'UserController@documents');
    Route::get('transactions/{id}/detail', 'UserController@transactionDetail');
    Route::post('investors/verify-documents', 'UserController@verifyDocuments');
    Route::resource('investors', 'UserController');

    Route::resource('roles', 'RoleController');
    Route::resource('email-templates', 'EmailTemplateController');

    Route::resource('pools', 'PoolController');
    
    Route::get('deposits/{id}/approve', 'DepositController@approve');
    Route::post('deposits/download-csv', 'DepositController@downloadCsv');
    Route::resource('deposits', 'DepositController');

    Route::resource('pool-investments', 'PoolInvestmentController');
    Route::get('pool-investments/{id}/approve', 'PoolInvestmentController@approve');
    Route::post('pool-investments/download-csv', 'PoolInvestmentController@downloadCsv');

    Route::get('withdraws/{id}/approve', 'WithdrawController@approve');
    Route::post('withdraws/download-csv', 'WithdrawController@downloadCsv');
    Route::resource('withdraws', 'WithdrawController');

    Route::post('preview-profits-import-file', 'ProfitController@previewFile');
    Route::resource('profits', 'ProfitController');
    Route::resource('pool-balances', 'PoolBalanceController');
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

Route::get('/', 'HomeController@index');
Route::get('/resend-email', 'HomeController@resendEmail');
Route::get('/verify-account/{id}', 'HomeController@verifyAccount');

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {

    Route::post('otp-auth/verify-two-factor-authentication', 'OtpAuthController@verifyTwoFactorAuthentication');
    Route::get('otp-auth/reset-two-factor-authentication', 'OtpAuthController@resetTwoFactorAuthentication');

    // *************************** //
    //     Auth Routes
    // *************************** //

    Route::group(['middleware'=> ['auth','user.check.status']], function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::post('/monthly-statements', 'DashboardController@monthlyStatement');
        Route::get('/profile', 'UserController@profile');
        Route::post('/profile', 'UserController@updateProfile');

        Route::get('/documents', 'UserController@documents');
        Route::post('/documents', 'UserController@uploadDocuments');

        Route::get('otp-auth/info', 'OtpAuthController@info');
        Route::get('otp-auth/setup-two-factor-authentication', 'OtpAuthController@setupTwoFactorAuthentication');
        Route::post('otp-auth/enable-two-factor-authentication', 'OtpAuthController@enableTwoFactorAuthentication');
        Route::get('otp-auth/disable-two-factor-authentication', 'OtpAuthController@disableTwoFactorAuthentication');
        Route::get('otp-auth/send-email-code', 'OtpAuthController@sendEmailCode');

        Route::group(['middleware'=> ['user.check.kyc']], function () {
            Route::get('/pools/{id}/invest', 'PoolController@invest');
            Route::post('/invest', 'PoolController@saveInvestment');
            Route::get('/pools', 'PoolController@index');
            Route::get('/pools/{id}', 'PoolController@show');

            Route::resource('deposits', 'DepositController');
            Route::post('deposits/{id}/transfer', 'DepositController@transfer');
            Route::get('transactions', 'ListingController@transactions');
            Route::get('transactions/{id}', 'ListingController@transactionDetail');
            Route::get('balances', 'ListingController@balances');
            Route::get('current-month-statements', 'ListingController@currentMonthStatements');
            Route::get('pool-investments', 'PoolController@investments');
            Route::get('pool-investments/{id}/edit', 'PoolController@investmentEdit');
            Route::get('pool-investments/{id}', 'PoolController@investmentDetail');
            Route::post('pool-investments/{id}/transfer', 'PoolController@transfer');

            Route::resource('withdraws', 'WithdrawController');

            Route::get('invite-a-friend', 'ReferralController@inviteFriend');
        });
    });
});

// ******************* //
//     Test Routes
// ******************* //

Route::get('test', 'TestController@index');
