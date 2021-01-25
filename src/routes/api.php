<?php
use DaydreamLab\User\Controllers\User\Front\UserFrontController;
use DaydreamLab\User\Controllers\User\UserController;
use DaydreamLab\User\Controllers\User\Admin\UserAdminController;
use DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController;
 /************************************  前台 API  ************************************/

// 啟用帳號
Route::get('/api/user/activate/{token}', [UserFrontController::class, 'activate']);

// 寄送忘記密碼Email
Route::post('/api//password/email', [UserFrontController::class, 'sendResetLinkEmail']);

// 驗證忘記密碼token合法
Route::get('/api/password/reset/{token}', [UserFrontController::class, 'forgotPasswordTokenValidate']);

// 重設密碼
Route::post('/api/password/reset', [UserFrontController::class, 'resetPassword']);

// 檢查Email是否使用過
Route::post('/api/user/checkEmail', [UserFrontController::class, 'checkEmail'])
    ->middleware(['CORS']);

// 登入
Route::post('/api/user/login', [UserFrontController::class, 'login'])->name('login');

// 註冊
Route::post('/api/user/register', [UserFrontController::class, 'register']);

// 登出
Route::get('/api/user/logout', [UserController::class, 'logout']);

// 檢查token狀態
Route::get('/api/user/login', [UserFrontController::class, 'getLogin'])
    ->middleware(['expired']);

Route::get('/api/user', [UserFrontController::class, 'getItem'])
    ->middleware(['expired']);

//Route::get('/api/user/login/facebook', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbLogin');
//Route::get('/api/user/login/facebook/callback', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbCallback');




/************************************  後台 API  ************************************/
Route::get('api/admin/api/{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@getItem')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@remove')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@state')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/store','DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@store')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/search','DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController@search')
 ->middleware(['expired', 'admin']);

Route::get('api/admin/group/{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@getItem')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@remove')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@state')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/store','DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@store')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/search','DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController@search')
 ->middleware(['expired', 'admin']);

Route::post('api/admin/asset/remove', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@remove')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/state', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@state')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/store','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@store')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/search','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@search')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/ordering','DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@ordering')
 ->middleware(['expired', 'admin']);
Route::get('api/admin/asset/treeList', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@treeList')
 ->middleware(['expired', 'admin']);
Route::get('api/admin/asset/{id}', 'DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController@getItem')
 ->middleware(['expired', 'admin']);


Route::post('api/admin/user/block', [UserAdminController::class, 'block'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/user/search', [UserAdminController::class, 'search'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/user/page', [UserAdminController::class, 'getSelfPage'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/user/remove', [UserAdminController::class, 'remove'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/user/store', [UserAdminController::class, 'store'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/user/{id}', [UserAdminController::class, 'getItem'])
 ->middleware(['expired', 'admin']);


Route::post('api/admin/user/group/search', [UserGroupAdminController::class, 'search'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/user/group/remove', [UserGroupAdminController::class, 'remove'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/user/group/store', [UserGroupAdminController::class, 'store'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/user/group/tree', [UserGroupAdminController::class, 'tree'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/user/group/{id}', [UserGroupAdminController::class, 'getItem'])
    ->where('id', '[0-9]+')
 ->middleware(['expired', 'admin']);
Route::get('api/admin/user/group/{id}/page', [UserGroupAdminController::class, 'getPage'])
 ->middleware(['expired', 'admin']);


Route::post('api/admin/viewlevel/remove', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@remove')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/state', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@state')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/store','DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@store')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/search','DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@search')
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/ordering','DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@ordering')
 ->middleware(['expired', 'admin']);
Route::get('api/admin/viewlevel/{id}', 'DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController@getItem')
 ->middleware(['expired', 'admin']);

