<?php
// User

Route::group(['middleware' => ['api'], 'prefix' => 'api'], function (){


    Route::group(['prefix' => 'user'], function (){
        Route::post('register', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@register');

    });

////    Route::get('activate/{token}', 'API\User\Front\UserFrontController@activate');
////
////    Route::get('fblogin', 'API\User\Front\UserFrontController@fblogin');
////    Route::get('fblogin/callback', 'API\User\Front\UserFrontController@fbCallback');
////
////    Route::get('logout', 'API\User\UserController@logout');
////    Route::post('login', 'API\User\Front\UserFrontController@login');
//

//
//    // User Forget Password
////
////    Route::group(['prefix' => 'password'], function (){
////        Route::post('email','API\User\Front\UserFrontController@sendResetLinkEmail');
////        Route::post('reset/{token}','API\User\Front\UserFrontController@forgotPasswordTokenValidate');
////        Route::post('reset','API\User\Front\UserFrontController@resetPassword');
////    });
////
////
////    //Route::post('admin/login', 'API\PassportController@loginAdmin');
////    Route::post('admin/login', 'API\PassportController@loginAdmin');
////
//
});

//Route::group(['prefix' => 'asset'], function (){
//
//    Route::get('{asset_id}', 'API\Asset\AssetController@getItem');
//    Route::post('remove', 'API\Asset\AssetController@remove');
//    Route::post('search', 'API\Asset\AssetController@search');
//    Route::post('state', 'API\Asset\AssetController@state');
//    Route::post('store', 'API\Asset\AssetController@store');
//
//
//    //指派asset group
//    Route::group(['prefix' => '{asset_id}'], function () {
//        Route::get('groups', 'API\Asset\AssetController@getGroups');
//        Route::post('groups/store', 'API\AssetController@updateAssetGroups');
//    });
//
//
//    // asset group
//    Route::group(['prefix' => 'group'], function () {
//        Route::get('{group_id}', 'API\Asset\AssetGroupController@getItem');
//        Route::post('remove', 'API\Asset\AssetGroupController@remove');
//        Route::post('search', 'API\Asset\AssetGroupController@search');
//        Route::post('state', 'API\Asset\AssetGroupController@state');
//        Route::post('store', 'API\Asset\AssetGroupController@store');
//
//
//        Route::group(['prefix' => 'map'], function () {
//            Route::post('store', 'API\Asset\AssetGroupMapController@store');
//        });
//
//    });
//
//    // Asset Api
//    Route::group(['prefix' => 'api'], function () {
//        Route::get('{api_id}', 'API\Asset\AssetApiController@getItem');
//        Route::post('remove', 'API\Asset\AssetApiController@remove');
//        Route::post('search', 'API\Asset\AssetApiController@search');
//        Route::post('state', 'API\Asset\AssetApiController@state');
//        Route::post('store', 'API\Asset\AssetApiController@store');
//
//
//        Route::group(['prefix' => 'map'], function () {
//            Route::post('store', 'API\Asset\AssetApiMapController@store');
//        });
//
//    });
//
//});