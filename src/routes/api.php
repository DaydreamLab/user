<?php

Route::group(['middleware' => ['api'], 'prefix' => 'api'], function (){

    Route::group(['prefix' => 'user'], function (){
        Route::post('register', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@register');
        Route::get('activate/{token}', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@activate');
        Route::post('password/reset', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@changePassword');
        Route::get('login/facebook', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbLogin');
        Route::get('login/facebook/callback', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbCallback');
        Route::get('logout', 'DaydreamLab\User\Controllers\User\UserController@logout');
        Route::post('login', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@login');

    });

    // 忘記密碼
    Route::group(['prefix' => 'password'], function (){
        Route::post('email','DaydreamLab\User\Controllers\User\Front\UserFrontController@sendResetLinkEmail');
        Route::get('reset/{token}','DaydreamLab\User\Controllers\User\Front\UserFrontController@forgotPasswordTokenValidate');
        Route::post('reset','DaydreamLab\User\Controllers\User\Front\UserFrontController@resetPassword');
    });

    // ----- Admin Routes -----
    Route::group(['middleware' => ['auth:api', 'expired', 'admin'], 'prefix' => 'admin'], function (){

        // ----- Asset -----
        Route::group(['prefix' => 'asset'], function (){

            Route::post('remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@search');
            Route::post('ordering','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@ordering');
            Route::get('treeList', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@treeList');
            Route::get('{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getItem');
            Route::get('{id}/groups', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getGroups');
            Route::get('{id}/apis', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getApis');


            Route::group(['prefix' => 'api'], function (){
                Route::get('{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@getItem');
                Route::post('remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@remove');
                Route::post('state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@state');
                Route::post('store','DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@store');
                Route::post('search','DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@search');


                Route::group(['prefix' => 'map'], function (){
                    Route::post('store', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@storeMaps');
                });
            });


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
        });


        // ----- Role -----
        Route::group(['prefix' => 'role'], function (){
            Route::get('tree', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getTree');
            Route::get('{id}', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@getItem');


            Route::post('remove', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@search');
            Route::post('ordering','DaydreamLab\User\Controllers\Role\Admin\RoleAdminController@ordering');

            Route::group(['prefix' => 'api'], function (){
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

        // User
        Route::group(['prefix' => 'user'], function (){
            Route::post('block', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@block');
            Route::post('search', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@search');
            Route::get('page', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getSelfPage');
            Route::get('apis', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getApis');
            Route::get('action', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getAction');
            Route::post('remove', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@remove');
            Route::post('store', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@store');
            Route::get('{id}', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getItem');
            Route::get('{id}/page', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getUserPage');
            Route::get('{id}/apis', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getUserPage');


            Route::group(['prefix' => 'group'], function (){
                Route::post('search', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@search');
                Route::post('remove', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@remove');
                Route::post('store', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@store');
                Route::get('tree', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@tree');
                Route::get('treeList', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@treeList');
                Route::get('{id}', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getItem');

                /**
                 *
                 */
                Route::get('{id}/page', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getPage');
                Route::get('{id}/apiids', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getApiIds');
                Route::get('{id}/apis', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getApis');
                Route::get('{id}/action', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getAction');


            });


            Route::group(['prefix' => 'role'], function (){
                Route::group(['prefix' => 'map'], function (){
                    Route::post('store', 'DaydreamLab\User\Controllers\User\Admin\UserRoleMapAdminController@store');
                });
            });
        });


        Route::group(['prefix' => 'viewlevel'], function (){
            Route::get('list', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@getList');
            Route::post('remove', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@remove');
            Route::post('state', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@state');
            Route::post('store','DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@store');
            Route::post('search','DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@search');
            Route::get('{id}', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@getItem');
        });

    });
});



