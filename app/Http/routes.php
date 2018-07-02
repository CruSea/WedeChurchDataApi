<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => '/api'], function () {

Route::post('authenticate', 'JwtAuthenticateController@authenticate');
Route::get('isauth', 'JwtAuthenticateController@isAuthenticated');
Route::post('register', 'JwtAuthenticateController@createUser');

Route::group([ 'middleware' => 'authenticated'], function()
{
Route::group(['prefix' => '/admin', 'middleware' => ['ability:admin, create users']], function()
{
        Route::delete('users/{users}', 'JwtAuthenticateController@destroyUser');
        Route::get('users', 'JwtAuthenticateController@index');

        Route::get('roles/all', 'RolesController@getRoles');
        Route::get('roles', 'RolesController@paginatedRoles');
        Route::post('roles', 'RolesController@store');
        Route::put('roles', 'RolesController@update');
        Route::delete('roles/{id}', 'RolesController@destroy');

        Route::post('permissions', 'PermissionsController@store');
        Route::put('permissions', 'PermissionsController@update');
        Route::get('permissions/all', 'JwtAuthenticateController@getPermissions');
        Route::get('permissions', 'PermissionsController@paginatedPermissions');
        Route::delete('permissions/{id}', 'PermissionsController@destroy');

        Route::post('assign-role', 'JwtAuthenticateController@assignRole');
        Route::post('attach-permission', 'JwtAuthenticateController@attachPermission');
        Route::post('check', 'JwtAuthenticateController@checkRoles');
        Route::post('check-permissions', 'JwtAuthenticateController@checkPermissions');

        Route::post('denominations', 'DenominationsController@store');
        Route::put('denominations/{denominations}', 'DenominationsController@update');
        Route::delete('denominations/{denominations}', 'DenominationsController@destroy');
        Route::get('denominations/all', 'DenominationsController@alldeno');

        Route::delete('churches/{churches}', 'ChurchesController@destroy');
        Route::get('denominations/names', 'ChurchesLogicController@getDenoNames');
        Route::get('churches/count', 'ChurchesLogicController@countChurch');
        Route::get('churches/by-denomination/count', 'ChurchesLogicController@countChurchByDenomination');
        //verify church
        Route::post('verify/{verify}', 'ChurchesController@verify');



});

// Route::resource('churches','ChurchesController');
// Route::resource('denominations','DenominationsController');

//church api
Route::get('churches', 'ChurchesController@index');
Route::post('churches', 'ChurchesController@store');
Route::put('churches/{churches}', 'ChurchesController@update');
Route::get('churches/{churches}', 'ChurchesController@show');
//denomination api
Route::get('denominations', 'DenominationsController@index');
Route::get('denominations/{denominations}', 'DenominationsController@show');

});
Route::post('request-password-reset', 'PasswordResetController@sendEmail');
Route::post('change-password', 'ChangePasswordController@process');


});
