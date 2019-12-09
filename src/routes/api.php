 <?php

 /************************************  前台 API  ************************************/
 Route::post('api/user/register', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@register');
 Route::post('api/user/checkEmail', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@checkEmail')
     ->middleware(['CORS']);
 Route::get('api/user/activate/{token}', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@activate');
 Route::post('api/user/password/reset', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@changePassword')
     ->middleware(['auth:api']);
 Route::get('api/user/login/facebook', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbLogin');
 Route::get('api/user/login/facebook/callback', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@fbCallback');
 Route::get('api/user/logout', 'DaydreamLab\User\Controllers\User\UserController@logout');
 Route::get('api/user/login', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@getLogin')
     ->middleware(['expired']);
 Route::post('api/user/login', 'DaydreamLab\User\Controllers\User\Front\UserFrontController@login')->name('login');

 Route::post('api/user/password/email','DaydreamLab\User\Controllers\User\Front\UserFrontController@sendResetLinkEmail');
 Route::get('api/user/password/reset/{token}','DaydreamLab\User\Controllers\User\Front\UserFrontController@forgotPasswordTokenValidate');
 Route::post('api/user/password/reset','DaydreamLab\User\Controllers\User\Front\UserFrontController@resetPassword');



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



 Route::post('api/admin/user/block', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@block')
     ->middleware(['expired', 'admin']);
 Route::post('api/admin/user/search', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@search')
     ->middleware(['expired', 'admin']);
 Route::get('api/admin/user/page', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getSelfPage')
     ->middleware(['expired', 'admin']);
 Route::post('api/admin/user/remove', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@remove')
     ->middleware(['expired', 'admin']);
 Route::post('api/admin/user/store', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@store')
     ->middleware(['expired', 'admin']);
 Route::get('api/admin/user/{id}', 'DaydreamLab\User\Controllers\User\Admin\UserAdminController@getItem')
     ->middleware(['expired', 'admin']);
 Route::post('api/admin/user/group/search', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@search')
     ->middleware(['expired', 'admin']);
 Route::post('api/admin/user/group/remove', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@remove')
     ->middleware(['expired', 'admin']);
 Route::post('api/admin/user/group/store', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@store')
     ->middleware(['expired', 'admin']);
 Route::get('api/admin/user/group/tree', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@tree')
     ->middleware(['expired', 'admin']);
 Route::get('api/admin/user/group/{id}', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getItem')->where('id', '[0-9]+')
     ->middleware(['expired', 'admin']);
 Route::get('api/admin/user/group/{id}/page', 'DaydreamLab\User\Controllers\User\Admin\UserGroupAdminController@getPage')
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

