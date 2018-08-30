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
            Route::get('{id}/groups', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getGroups');
            Route::get('{id}/apis', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getApis');
            Route::post('remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@search');



            Route::group(['prefix' => 'group'], function (){
                Route::get('{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@getItem');
                Route::post('remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@remove');
                Route::post('state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@state');
                Route::post('store','DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@store');
                Route::post('search','DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@search');


                Route::group(['prefix' => 'map'], function (){
                    Route::post('store', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupMapAdminController@store');
                });
            });


            Route::group(['prefix' => 'api'], function (){
                Route::get('{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@getItem');
                Route::post('remove', 'Daydrea mLab\User\Controllers\Asset\Admin\AssetApiAdminController@remove');
                Route::post('state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@state');
                Route::post('store','DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@store');
                Route::post('search','DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@search');


                Route::group(['prefix' => 'map'], function (){
                    Route::post('store', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiMapAdminController@store');
                });
            });
        });


        // ----- Role -----
        Route::group(['prefix' => 'role'], function (){
            Route::get('tree', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getTree');
            Route::get('{id}', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getItem');
            Route::get('{id}/grant', 'DaydreamLab\User\Controllers\Role\Admin\RoleAssetMapAdminController@getGrant');
            Route::post('grant/store', 'DaydreamLab\User\Controllers\Role\Admin\RoleAssetMapAdminController@store');
            Route::get('{id}/page', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getPage');
            Route::get('{id}/apisids', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getApisIds');
            Route::get('{id}/apis', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getApis');
            Route::get('{id}/action', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getAction');

            Route::post('remove', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@search');

            Route::group(['prefix' => 'apis'], function (){
                Route::group(['prefix' => 'map'], function (){
                    Route::post('store', 'DaydreamLab\User\Controllers\Role\Admin\RoleApiMapAdminController@store');
                });
            });


            Route::group(['prefix' => 'asset'], function (){
                Route::group(['prefix' => 'map'], function (){
                    Route::post('store', 'DaydreamLab\User\Controllers\Role\Admin\RoleAssetMapAdminController@store');
                });
            });
        });


    });
});



