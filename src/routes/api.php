<?php

use DaydreamLab\User\Controllers\User\UserController;
use DaydreamLab\User\Controllers\Api\Admin\ApiAdminController;
use DaydreamLab\User\Controllers\User\Admin\UserAdminController;
use DaydreamLab\User\Controllers\User\Front\UserFrontController;
use DaydreamLab\User\Controllers\Asset\Admin\AssetAdminController;
use DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController;
use DaydreamLab\User\Controllers\Company\Admin\CompanyAdminController;
use DaydreamLab\User\Controllers\Company\Front\CompanyFrontController;
use DaydreamLab\User\Controllers\Asset\Admin\AssetGroupAdminController;
use DaydreamLab\User\Controllers\Viewlevel\Admin\ViewlevelAdminController;
use DaydreamLab\User\Controllers\Xsms\XsmsController;
use DaydreamLab\User\Controllers\CompanyOrder\Admin\CompanyOrderAdminController;
use DaydreamLab\User\Controllers\CompanyOrderItem\Admin\CompanyOrderItemAdminController;
use DaydreamLab\User\Controllers\UserTag\Admin\UserTagAdminController;
use DaydreamLab\User\Controllers\NotificationTemplate\NotificationTemplateController;
/************************************  前台 API  ************************************/

// 啟用帳號
//Route::get('/api/user/activate/{token}', [UserFrontController::class, 'activate']);

// 寄送忘記密碼Email
//Route::post('/api/password/email', [UserFrontController::class, 'sendResetLinkEmail']);

// 驗證忘記密碼token合法
//Route::get('/api/password/reset/{token}', [UserFrontController::class, 'forgotPasswordTokenValidate']);

// 重設密碼
//Route::post('/api/password/reset', [UserFrontController::class, 'resetPassword']);

Route::post('api/xsms/querySms', [XsmsController::class, 'querySms'])->middleware(['admin']);

# 取得公司資訊
Route::get('api/company/{vat}', [CompanyFrontController::class, 'getInfo']);

# 檢查Email是否使用過
Route::post('/api/user/checkEmail', [UserFrontController::class, 'checkEmail'])->middleware(['CORS']);

# 檢查手機是否已經註冊過
Route::post('/api/user/checkMobilePhone', [UserFrontController::class, 'checkMobilePhone']);

# 更新舊會員資料
Route::post('api/user/oldUserUpdate', [UserFrontController::class, 'updateOldUser']);

# 編輯會員資料
Route::post('/api/user/store', [UserFrontController::class, 'store'])->middleware(['expired']);

# 寄送經銷商Email驗證信
Route::get('/api/user/dealer/sendValidateEmail', [UserFrontController::class, 'sendValidateEmail'])
    ->middleware(['expired', 'throttle:15,15']);

# 驗證經銷商資格
Route::get('/api/user/dealer/validate/{token}', [UserFrontController::class, 'dealerValidate'])
    ->middleware(['throttle:15,15']);

# 取得手機驗證碼
Route::post('/api/user/getCode', [UserFrontController::class, 'getVerificationCode'])->middleware(['throttle:5,5']);

# 驗證手機驗證碼
Route::post(
    '/api/user/verifyCode',
    [UserFrontController::class, 'verifyVerificationCode']
)->middleware(['throttle:15,5']);

# 登入
Route::post('/api/user/login', [UserFrontController::class, 'login'])->name('login')->middleware(['throttle:15,5']);

# 註冊
Route::post('/api/user/register', [UserFrontController::class, 'register']);

# 登出
Route::get('/api/user/logout', [UserController::class, 'logout']);

# 檢查token狀態
Route::get('/api/user/getLogin', [UserFrontController::class, 'getLogin'])->middleware(['expired']);

# 綁定 Line ID
Route::post('/api/user/lineBind', [UserFrontController::class, 'lineBind'])->middleware(['expired']);

# 取得使用者資訊
Route::get('/api/user/profile', [UserFrontController::class, 'getItem'])->middleware(['expired']);
# 取得舊會員資料
Route::get('/api/user/profile/{uuid}', [UserFrontController::class, 'getByUUID']);

//Route::get('/api/user/login/facebook', [UserFrontController::class, 'fbLogin']);
//Route::get('/api/user/login/facebook/callback', [UserFrontController::class, 'fbCallback']);

# Line bot
Route::get('api/linebot/richmenu', [UserFrontController::class, 'lineRichmenu']);
Route::post('api/linebot/callback', [UserFrontController::class, 'lineBotChat']);
Route::get('api/linebot/linkAccount/{lineId}', [UserFrontController::class, 'linkAccount']);

/************************************  後台 API  ************************************/

# 發送 totp mail
Route::post('api/admin/user/sendTotp', [UserAdminController::class, 'sendTotp'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);

# Api
Route::post('api/admin/api/ordering', [ApiAdminController::class, 'ordering'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/api/remove', [ApiAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/api/state', [ApiAdminController::class, 'state'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/api/store', [ApiAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/api/search', [ApiAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/api/{id}', [ApiAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);


# Asset
Route::post('api/admin/asset/ordering', [AssetAdminController::class, 'ordering'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/remove', [AssetAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/state', [AssetAdminController::class, 'state'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/store', [AssetAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/search', [AssetAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/asset/{id}', [AssetAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);



# Asset Group
Route::get('api/admin/asset/group/page', [AssetGroupAdminController::class, 'page'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/group/ordering', [AssetGroupAdminController::class, 'ordering'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/group/remove', [AssetGroupAdminController::class, 'remove'])
 ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/group/state', [AssetGroupAdminController::class, 'state'])
 ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/group/store', [AssetGroupAdminController::class, 'store'])
 ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/asset/group/search', [AssetGroupAdminController::class, 'search'])
 ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/asset/group/{id}', [AssetGroupAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);

# CompanyOrder
Route::get('api/admin/company/{companyId}/order/{orderId}', [CompanyOrderAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/{companyId}/order/store', [CompanyOrderAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/{companyId}/order/search', [CompanyOrderAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/{companyId}/order/remove', [CompanyOrderAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/{companyId}/order/import', [CompanyOrderAdminController::class, 'import'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);

# Company
Route::post('api/admin/company/{id}/user/search', [CompanyAdminController::class, 'searchUsers'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/{id}/user/export', [CompanyAdminController::class, 'exportSearchUsers'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/export', [CompanyAdminController::class, 'export'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/restore', [CompanyAdminController::class, 'restore'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/ordering', [CompanyAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/remove', [CompanyAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/store', [CompanyAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/search', [CompanyAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/company/{id}', [CompanyAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/company/importCompany', [CompanyAdminController::class, 'importCompany'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);


# User
Route::post('api/admin/user/export', [UserAdminController::class, 'export'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/block', [UserAdminController::class, 'block'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/search', [UserAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/crm/search', [UserAdminController::class, 'crmSearch'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/user/page', [UserAdminController::class, 'getSelfPage'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/remove', [UserAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/store', [UserAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/user/{id}', [UserAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);



# 會員標籤(UserTag)
Route::post('/api/admin/user/tag/store', [UserTagAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('/api/admin/user/tag/search', [UserTagAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('/api/admin/user/tag/state', [UserTagAdminController::class, 'state'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('/api/admin/user/tag/{id}', [UserTagAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('/api/admin/user/tag/{id}/users/edit', [UserTagAdminController::class, 'editUsers'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('/api/admin/user/tag/{id}/users', [UserTagAdminController::class, 'getUsers'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);

Route::post('api/admin/user/group/search', [UserGroupAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/group/remove', [UserGroupAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/user/group/store', [UserGroupAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/user/group/tree', [UserGroupAdminController::class, 'tree'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/user/group/{id}', [UserGroupAdminController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);


Route::post('api/admin/viewlevel/remove', [ViewlevelAdminController::class, 'remove'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/viewlevel/store', [ViewlevelAdminController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/viewlevel/search', [ViewlevelAdminController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/viewlevel/ordering', [ViewlevelAdminController::class, 'ordering'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::get('api/admin/viewlevel/{id}', [ViewlevelAdminController::class, 'getItem'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);


Route::post('api/admin/notification/template/store', [NotificationTemplateController::class, 'store'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
Route::post('api/admin/notification/template/search', [NotificationTemplateController::class, 'search'])
    ->middleware(['expired', 'admin', 'restrict-ip:admin']);
