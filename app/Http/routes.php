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

Route::post('user', 'JwtAuthenticateController@createUser');
Route::group(['prefix' => 'admin', 'middleware' => ['ability:admin, create users']], function()
{
        Route::get('users', 'JwtAuthenticateController@index');
        Route::post('role', 'JwtAuthenticateController@createRole');
        Route::post('permission', 'JwtAuthenticateController@createPermission');
        Route::post('assign-role', 'JwtAuthenticateController@assignRole');
        Route::post('attach-permission', 'JwtAuthenticateController@attachPermission');
        Route::post('check', 'JwtAuthenticateController@checkRoles');

        Route::post('denominations', 'DenominationsController@store');
        Route::put('denominations/{denominations}', 'DenominationsController@update');
        Route::delete('denominations/{denominations}', 'DenominationsController@destroy');

        Route::delete('churches/{churches}', 'ChurchesController@destroy');
        //verify church
        Route::post('verify/{verify}', 'ChurchesController@verify');
});

Route::post('authenticate', 'JwtAuthenticateController@authenticate');
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


