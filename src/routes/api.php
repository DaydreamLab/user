<?php

Route::group(['middleware' => ['api'], 'prefix' => 'api'], function (){

    Route::group(['prefix' => 'user'], function (){
        Route::post('register', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@register');
        Route::get('fblogin', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fblogin');
        Route::get('fblogin/callback', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbCallback');
        Route::get('logout', 'DaydreamLab\User\Controllers\User\UserController@logout');
        Route::post('login', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@login');


        // 忘記密碼
        Route::group(['prefix' => 'password'], function (){
            Route::post('email','DaydreamLab\User\Controllers\User\Front\UserFrontController@sendResetLinkEmail');
            Route::post('reset/{token}','DaydreamLab\User\Controllers\User\Front\UserFrontController@forgotPasswordTokenValidate');
            Route::post('reset','DaydreamLab\User\Controllers\User\Front\UserFrontController@resetPassword');
        });

    });




    // ----- Admin Routes -----
    Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'admin'], function (){

        Route::group(['prefix' => 'user'], function (){
            Route::get('{id}', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getItem');

        });

        // ----- Asset -----
        Route::group(['prefix' => 'asset'], function (){
            Route::get('{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getItem');
            Route::post('remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@search');
        });


        // ----- Role -----
        Route::group(['prefix' => 'role'], function (){
            Route::get('{id}', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getItem');
            Route::post('remove', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@search');
        });



    });
});



