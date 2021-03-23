<?php
use DaydreamLab\User\Controllers\User\Front\UserFrontController;
use DaydreamLab\User\Controllers\User\UserController;
use DaydreamLab\User\Controllers\User\Admin\UserAdminController;
use DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController;
use DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController;
use DaydreamLab\User\Controllers\Asset\Admin\AssetApiAdminController;
use DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController;
use DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController;
use DaydreamLab\User\Controllers\User\Admin\UserTagAdminController;

 /************************************  前台 API  ************************************/

// 啟用帳號
Route::get('/api/user/activate/{token}', [UserFrontController::class, 'activate']);

// 寄送忘記密碼Email
Route::post('/api/password/email', [UserFrontController::class, 'sendResetLinkEmail']);

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

Route::post('/api/user/edit', [UserFrontController::class, 'editProfile'])
    ->middleware(['expired']);

Route::get('/api/user/login/facebook', [UserFrontController::class, 'fbLogin']);
Route::get('/api/user/login/facebook/callback', [UserFrontController::class, 'fbCallback']);
//

/************************************  後台 API  ************************************/

Route::post('api/admin/asset/remove', [AssetAdminController::class, 'remove'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/state',  [AssetAdminController::class, 'state'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/store',  [AssetAdminController::class, 'store'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/search',  [AssetAdminController::class, 'search'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/asset/ordering',  [AssetAdminController::class, 'ordering'])
    ->middleware(['expired', 'admin']);
Route::get('api/admin/asset/treeList',  [AssetAdminController::class, 'treeList'])
    ->middleware(['expired', 'admin']);
Route::get('api/admin/asset/{id}',  [AssetAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin']);


Route::post('api/admin/api/remove', [AssetApiAdminController::class, 'remove'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/state', [AssetApiAdminController::class, 'state'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/store', [AssetApiAdminController::class, 'store'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/api/search', [AssetApiAdminController::class, 'search'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/api/{id}', [AssetApiAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin']);

Route::post('api/admin/group/remove', [AssetGroupAdminController::class, 'remove'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/state', [AssetGroupAdminController::class, 'state'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/store', [AssetGroupAdminController::class, 'store'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/group/search', [AssetGroupAdminController::class, 'search'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/group/{id}', [AssetGroupAdminController::class, 'getItem'])
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


Route::post('api/admin/user/tag/search', [UserTagAdminController::class, 'search'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/user/tag/remove', [UserTagAdminController::class, 'remove'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/user/tag/store', [UserTagAdminController::class, 'store'])
    ->middleware(['expired', 'admin']);
Route::post('api/admin/user/tag/apply', [UserTagAdminController::class, 'apply'])
    ->middleware(['expired', 'admin']);
Route::get('api/admin/user/tag/{id}}', [UserTagAdminController::class, 'getItem'])
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


Route::post('api/admin/viewlevel/remove', [ViewlevelAdminController::class, 'remove'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/store', [ViewlevelAdminController::class, 'store'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/search', [ViewlevelAdminController::class, 'search'])
 ->middleware(['expired', 'admin']);
Route::post('api/admin/viewlevel/ordering', [ViewlevelAdminController::class, 'ordering'])
 ->middleware(['expired', 'admin']);
Route::get('api/admin/viewlevel/{id}', [ViewlevelAdminController::class, 'getItem'])
 ->middleware(['expired', 'admin']);

