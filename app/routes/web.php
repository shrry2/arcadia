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

/**
 * 認証ルート
 */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

/**
 * 認証ルート以外全体をイントラネットまたは権限グループ制限で保護
 */
Route::group(['middleware' => ['intranetOrAuthenticated']], function () {
    /**
     * イントラネット共用画面
     */
    // ダッシュボード
    Route::get('/', 'HomeController@index')->name('home');

    // イントラネットからの出退勤登録
    Route::group(['middleware' => ['intranetOnly']], function () {
        Route::get('attendance/begin', 'AttendanceController@begin')->name('attendance.begin');
        Route::post('attendance/begin', 'AttendanceController@storeBegin')->name('attendance.begin.store');
        Route::get('attendance/member', 'AttendanceController@apiListWorkingMembers')->name('attendance.api.listMembers');
        Route::post('attendance/member/finish', 'AttendanceController@apiFinishWorking')->name('attendance.api.finish');
    });

    // ボード
    Route::get('board/{id}/task', 'BoardController@apiListTask')->name('board.task.index');
    Route::post('board/{id}/task', 'BoardController@apiStoreTask')->name('board.task.store');
    Route::resource('board', 'BoardController');

    // タスク
    Route::get('task/search_member', 'TaskController@searchMember')->name('task.searchMember');
    Route::resource('task', 'TaskController');

    /**
     * 個人認証済みのみ利用可能なルート
     */
    Route::group(['middleware' => ['auth']], function () {
        // プロフィール
        Route::get('profile', 'ProfileController@edit')->name('profile');
        Route::post('profile/update', 'ProfileController@update')->name('profile.update');

        // スタッフ登録
        Route::group(['middleware' => ['can:register users']], function () {
            Route::get('user/create', 'Auth\RegisterController@index')->name('user.new');
            Route::post('user/create', 'Auth\RegisterController@register');
        });

        // スタッフ一覧・管理
        Route::group(['middleware' => ['auth','can:edit users']], function () {
            Route::get('user', 'UserController@index')->name('user.list');
            Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
            Route::post('user/{id}/update', 'UserController@update')->name('user.update');
            Route::get('user/{id}/password', 'UserController@editPassword')->name('user.editPassword');
            Route::post('user/{id}/password/update', 'UserController@updatePassword')->name('user.updatePassword');
            Route::get('user/{id}/delete', 'UserController@delete')->name('user.delete');
            Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');
        });

        // 勤怠管理
        Route::resource('attendance', 'AttendanceController');

        // 権限グループの編集
        Route::group(['middleware' => ['can:edit permissions']], function () {
            Route::post('users/{id}/role/update', 'UserController@updateRole')->name('user.updateRole');
        });

        // 出退勤記録の編集
//        Route::group(['middleware' => ['can:edit attendance']], function () {
//            Route::resource('attendance', 'AttendanceController');
//        });

        /**
         * システム設定
         */
        Route::group(['middleware' => ['can:edit master settings']], function () {
            // 全般設定
            Route::get('setting', 'SettingController@edit')->name('settings');
            Route::post('setting/update', 'SettingController@update')->name('settings.update');

            // 部署設定
            Route::get('office', 'OfficeController@index')->name('office.list');
            Route::get('office/new', 'OfficeController@add')->name('office.add');
            Route::post('office', 'OfficeController@create')->name('office.create');
            Route::get('office/{id}', 'OfficeController@edit')->name('office.edit');
            Route::post('office/{id}', 'OfficeController@update')->name('office.update');
            Route::get('office/{id}/delete', 'OfficeController@delete')->name('office.delete');
            Route::delete('office/{id}', 'OfficeController@destroy')->name('office.destroy');

            // 部署イントラネット設定
            Route::get('intranet', 'IntranetController@index')->name('intranet.list');
            Route::get('intranet/new', 'IntranetController@new')->name('intranet.new');
            Route::post('intranet', 'IntranetController@create')->name('intranet.create');
            Route::get('intranet/{id}', 'IntranetController@edit')->name('intranet.edit');
            Route::post('intranet/{id}', 'IntranetController@update')->name('intranet.update');
            Route::get('intranet/{id}/delete', 'IntranetController@delete')->name('intranet.delete');
            Route::delete('intranet/{id}', 'IntranetController@destroy')->name('intranet.destroy');

            // 権限グループ設定
            Route::get('role', 'RoleController@index')->name('role.list');
            Route::get('role/new', 'RoleController@add')->name('role.add');
            Route::post('role', 'RoleController@create')->name('role.create');
            Route::get('role/{id}', 'RoleController@edit')->name('role.edit');
            Route::post('role/{id}/update', 'RoleController@update')->name('role.update');
            Route::get('role/{id}/delete', 'RoleController@delete')->name('role.delete');
            Route::delete('role/{id}', 'RoleController@destroy')->name('role.destroy');
        });
    });
});
