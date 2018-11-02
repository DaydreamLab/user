<?php

return [
    'USER_INSUFFICIENT_PERMISSION_ADMIN' => [
        'code'      => 403,
        'message'   => 'Insufficient permission admin'
    ],

    'USER_TOKEN_EXPIRED' => [
        'code'      => 403,
        'message'   => 'Token expired. Please login again'
    ],

    // Check email
    'USER_EMAIL_IS_REGISTERED' => [
        'code'      => 403,
        'message'   => 'Email is registered'
    ],

    'USER_EMAIL_IS_NOT_REGISTERED' => [
        'code'      => 200,
        'message'   => 'Email is not registered'
    ],



    // Register
    'USER_REGISTER_SUCCESS' => [
        'code'      => 200,
        'message'   => 'Register success. Please check your email to activate account'
    ],

    'USER_REGISTER_FAIL' => [
        'code'      => 500,
        'message'   => 'Register fail'
    ],



    // Login
    'USER_LOGIN_SUCCESS' => [
        'code'      => 200,
        'message'   => 'Login success'
    ],

    'USER_LOGIN_FAIL' => [
        'code'      => 500,
        'message'   => 'Login fail'
    ],

    'USER_EMAIL_OR_PASSWORD_INCORRECT' => [
        'code'      => 400,
        'message'   => 'Email or password incorrect'
    ],

    'USER_UNACTIVATED' => [
        'code'      => 403,
        'message'   => 'User has not been activated.We have sent you an activation email. Please check your email to activate account'
    ],

    'USER_UNAUTHORIZED' => [
        'code'      => 401,
        'message'   => 'User unauthorized'
    ],

    'USER_NOT_FOUND' => [
        'code'      => 400,
        'message'   => 'User not found'
    ],
    'USER_IS_BLOCKED' => [
        'code'      => 403,
        'message'   => 'User is blocked'
    ],


    // Change Password
    'USER_CHANGE_PASSWORD_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User change password success. Please login again.'
    ],
    'USER_CHANGE_PASSWORD_FAIL' => [
        'code'      => 500,
        'message'   => 'User change password fail'
    ],
    'USER_OLD_PASSWORD_INCORRECT' => [
        'code'      => 400,
        'message'   => 'User old password incorrect'
    ],



    // Reset Password
    'USER_RESET_PASSWORD_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User reset password success.'
    ],
    'USER_RESET_PASSWORD_FAIL' => [
        'code'      => 500,
        'message'   => 'User reset password fail.'
    ],
    'USER_RESET_PASSWORD_EMAIL_SEND' => [
        'code'      => 200,
        'message'   => 'User reset password email send. Please check your email to get reset password link.'
    ],
    'USER_RESET_PASSWORD_TOKEN_VALID' => [
        'code'      => 200,
        'message'   => 'User reset password token valid.'
    ],
    'USER_RESET_PASSWORD_TOKEN_INVALID' => [
        'code'      => 400,
        'message'   => 'User reset password token invalid.'
    ],
    'USER_RESET_PASSWORD_TOKEN_EXPIRED' => [
        'code'      => 403,
        'message'   => 'User reset password token expired. Please retry forget password.'
    ],

    // Logout
    'USER_LOGOUT_SUCCESS' => [
        'code'      => 200,
        'message'   => 'Logout success.'
    ],



    // Activation
    'USER_HAS_BEEN_ACTIVATED' => [
        'code'      => 403,
        'message'   => 'User has been activated'
    ],

    'USER_ACTIVATION_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User activation success'
    ],

    'USER_ACTIVATION_TOKEN_INVALID' => [
        'code'      => 400,
        'message'   => 'User activation token is invalid'
    ],

    // Uncategory
    'USER_ACCESS_DENIED' => [
        'code'      => 403,
        'message'   => 'User access denied'
    ],





    // Create
    'USER_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User create success'
    ],
    'USER_ADMIN_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin create success'
    ],
    'USER_FRONT_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front create success'
    ],
    'USER_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User create fail'
    ],
    'USER_ADMIN_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin create fail'
    ],
    'USER_FRONT_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User front create fail'
    ],



    // Update
    'USER_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User update success'
    ],
    'USER_ADMIN_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin update success'
    ],
    'USER_FRONT_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front update success'
    ],

    'USER_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User update fail'
    ],
    'USER_ADMIN_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin update fail'
    ],
    'USER_FRONT_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User front update fail'
    ],


    // Trash
    'USER_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User trash success'
    ],
    'USER_ADMIN_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin trash success'
    ],
    'USER_FRONT_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front trash success'
    ],

    'USER_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User trash fail'
    ],
    'USER_ADMIN_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin trash fail'
    ],
    'USER_FRONT_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User front trash fail'
    ],



    // Delete
    'USER_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User delete success'
    ],
    'USER_ADMIN_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin delete success'
    ],
    'USER_FRONT_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front delete success'
    ],


    'USER_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User delete fail'
    ],
    'USER_ADMIN_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin delete fail'
    ],
    'USER_FRONT_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User front delete fail'
    ],


    // Publish
    'USER_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User publish success'
    ],
    'USER_ADMIN_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin publish success'
    ],
    'USER_FRONT_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front publish success'
    ],

    'USER_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User publish fail'
    ],
    'USER_ADMIN_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin publish fail'
    ],
    'USER_FRONT_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User front publish fail'
    ],


    // Unpublish
    'USER_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User unpublish success'
    ],
    'USER_ADMIN_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin unpublish success'
    ],
    'USER_FRONT_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front unpublish success'
    ],
    'USER_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User unpublish fail'
    ],
    'USER_ADMIN_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin unpublish fail'
    ],
    'USER_FRONT_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User front unpublish fail'
    ],


    //Search
    'USER_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User search success'
    ],
    'USER_ADMIN_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin search success'
    ],
    'USER_FRONT_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front search success'
    ],
    'USER_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User search fail'
    ],
    'USER_ADMIN_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User admin search fail'
    ],
    'USER_FRONT_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User front search fail'
    ],


    // Get Item
    'USER_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User get item success'
    ],
    'USER_ADMIN_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin get item success'
    ],
    'USER_FRONT_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front get item success'
    ],
    'USER_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User get item fail'
    ],
    'USER_ADMIN_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User get item search fail'
    ],
    'USER_FRONT_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User front get item fail'
    ],

    'USER_GET_GRANT_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User get grant success'
    ],
    'USER_ADMIN_GET_GRANT_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin get grant success'
    ],
    'USER_FRONT_GET_GRANT_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front get grant success'
    ],

    'USER_GET_PAGE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User get page success'
    ],
    'USER_ADMIN_GET_PAGE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin get page success'
    ],
    'USER_FRONT_GET_PAGE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front get page success'
    ],

    'USER_GET_APIS_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User get apis success'
    ],
    'USER_ADMIN_GET_APIS_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin get apis success'
    ],
    'USER_FRONT_GET_APIS_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front get apis success'
    ],


    'USER_BLOCK_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User block success'
    ],
    'USER_ADMIN_BLOCK_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User admin block success'
    ],
    'USER_FRONT_BLOCK_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User front block success'
    ],
    'USER_UNBLOCK_SUCCESS' => [
        'code'      => 500,
        'message'   => 'User unblock success'
    ],
    'USER_ADMIN_UNBLOCK_SUCCESS' => [
        'code'      => 500,
        'message'   => 'User admin unblock success'
    ],
    'USER_FRONT_UNBLOCK_SUCCESS' => [
        'code'      => 500,
        'message'   => 'User front unblock success'
    ],


    
    // User Role Map
    // Create
    'USER_ROLE_MAP_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map create success'
    ],
    'USER_ROLE_MAP_ADMIN_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin create success'
    ],
    'USER_ROLE_MAP_FRONT_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front create success'
    ],
    'USER_ROLE_MAP_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map create fail'
    ],
    'USER_ROLE_MAP_ADMIN_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin create fail'
    ],
    'USER_ROLE_MAP_FRONT_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front create fail'
    ],



    // Update
    'USER_ROLE_MAP_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map update success'
    ],
    'USER_ROLE_MAP_ADMIN_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin update success'
    ],
    'USER_ROLE_MAP_FRONT_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front update success'
    ],

    'USER_ROLE_MAP_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map update fail'
    ],
    'USER_ROLE_MAP_ADMIN_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin update fail'
    ],
    'USER_ROLE_MAP_FRONT_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front update fail'
    ],


    // Trash
    'USER_ROLE_MAP_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map trash success'
    ],
    'USER_ROLE_MAP_ADMIN_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin trash success'
    ],
    'USER_ROLE_MAP_FRONT_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front trash success'
    ],

    'USER_ROLE_MAP_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map trash fail'
    ],
    'USER_ROLE_MAP_ADMIN_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin trash fail'
    ],
    'USER_ROLE_MAP_FRONT_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front trash fail'
    ],



    // Delete
    'USER_ROLE_MAP_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map delete success'
    ],
    'USER_ROLE_MAP_ADMIN_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin delete success'
    ],
    'USER_ROLE_MAP_FRONT_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front delete success'
    ],


    'USER_ROLE_MAP_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map delete fail'
    ],
    'USER_ROLE_MAP_ADMIN_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin delete fail'
    ],
    'USER_ROLE_MAP_FRONT_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front delete fail'
    ],


    // Publish
    'USER_ROLE_MAP_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map publish success'
    ],
    'USER_ROLE_MAP_ADMIN_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin publish success'
    ],
    'USER_ROLE_MAP_FRONT_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front publish success'
    ],

    'USER_ROLE_MAP_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map publish fail'
    ],
    'USER_ROLE_MAP_ADMIN_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin publish fail'
    ],
    'USER_ROLE_MAP_FRONT_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front publish fail'
    ],


    // Unpublish
    'USER_ROLE_MAP_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map unpublish success'
    ],
    'USER_ROLE_MAP_ADMIN_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin unpublish success'
    ],
    'USER_ROLE_MAP_FRONT_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front unpublish success'
    ],
    'USER_ROLE_MAP_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map unpublish fail'
    ],
    'USER_ROLE_MAP_ADMIN_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin unpublish fail'
    ],
    'USER_ROLE_MAP_FRONT_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front unpublish fail'
    ],


    //Search
    'USER_ROLE_MAP_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map search success'
    ],
    'USER_ROLE_MAP_ADMIN_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin search success'
    ],
    'USER_ROLE_MAP_FRONT_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front search success'
    ],
    'USER_ROLE_MAP_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map search fail'
    ],
    'USER_ROLE_MAP_ADMIN_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map admin search fail'
    ],
    'USER_ROLE_MAP_FRONT_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front search fail'
    ],

    // Get Item
    'USER_ROLE_MAP_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map get item success'
    ],
    'USER_ROLE_MAP_ADMIN_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map admin get item success'
    ],
    'USER_ROLE_MAP_FRONT_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User role map front get item success'
    ],
    'USER_ROLE_MAP_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map get item fail'
    ],
    'USER_ROLE_MAP_ADMIN_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map get item search fail'
    ],
    'USER_ROLE_MAP_FRONT_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User role map front get item fail'
    ],


    // ----- User Group -----
    // Create
    'USER_GROUP_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group create success'
    ],
    'USER_GROUP_ADMIN_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin create success'
    ],
    'USER_GROUP_FRONT_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front create success'
    ],
    'USER_GROUP_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group create fail'
    ],
    'USER_GROUP_ADMIN_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin create fail'
    ],
    'USER_GROUP_FRONT_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front create fail'
    ],


    // Create Nested
    'USER_GROUP_CREATE_NESTED_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group create nested success'
    ],
    'USER_GROUP_ADMIN_CREATE_NESTED_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin create nested success'
    ],
    'USER_GROUP_FRONT_CREATE_NESTED_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front create nested success'
    ],
    'USER_GROUP_CREATE_NESTED_FAIL' => [
        'code'      => 500,
        'message'   => 'User group create nested fail'
    ],
    'USER_GROUP_ADMIN_CREATE_NESTED_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin create nested fail'
    ],
    'USER_GROUP_FRONT_CREATE_NESTED_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front create nested fail'
    ],



    // Update
    'USER_GROUP_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group update success'
    ],
    'USER_GROUP_ADMIN_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin update success'
    ],
    'USER_GROUP_FRONT_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front update success'
    ],

    'USER_GROUP_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group update fail'
    ],
    'USER_GROUP_ADMIN_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin update fail'
    ],
    'USER_GROUP_FRONT_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front update fail'
    ],


    // Trash
    'USER_GROUP_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group trash success'
    ],
    'USER_GROUP_ADMIN_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin trash success'
    ],
    'USER_GROUP_FRONT_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front trash success'
    ],

    'USER_GROUP_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group trash fail'
    ],
    'USER_GROUP_ADMIN_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin trash fail'
    ],
    'USER_GROUP_FRONT_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front trash fail'
    ],



    // Delete
    'USER_GROUP_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group delete success'
    ],
    'USER_GROUP_ADMIN_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin delete success'
    ],
    'USER_GROUP_FRONT_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front delete success'
    ],


    'USER_GROUP_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group delete fail'
    ],
    'USER_GROUP_ADMIN_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin delete fail'
    ],
    'USER_GROUP_FRONT_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front delete fail'
    ],


    // Publish
    'USER_GROUP_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group publish success'
    ],
    'USER_GROUP_ADMIN_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin publish success'
    ],
    'USER_GROUP_FRONT_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front publish success'
    ],

    'USER_GROUP_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group publish fail'
    ],
    'USER_GROUP_ADMIN_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin publish fail'
    ],
    'USER_GROUP_FRONT_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front publish fail'
    ],


    // Unpublish
    'USER_GROUP_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group unpublish success'
    ],
    'USER_GROUP_ADMIN_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin unpublish success'
    ],
    'USER_GROUP_FRONT_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front unpublish success'
    ],
    'USER_GROUP_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group unpublish fail'
    ],
    'USER_GROUP_ADMIN_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin unpublish fail'
    ],
    'USER_GROUP_FRONT_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front unpublish fail'
    ],


    // Archive
    'USER_GROUP_ARCHIVE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group archive success'
    ],
    'USER_GROUP_ADMIN_ARCHIVE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin archive success'
    ],
    'USER_GROUP_FRONT_ARCHIVE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front archive success'
    ],
    'USER_GROUP_ARCHIVE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group archive fail'
    ],
    'USER_GROUP_ADMIN_ARCHIVE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin archive fail'
    ],
    'USER_GROUP_FRONT_ARCHIVE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front archive fail'
    ],

    //Search
    'USER_GROUP_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group search success'
    ],
    'USER_GROUP_ADMIN_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin search success'
    ],
    'USER_GROUP_FRONT_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front search success'
    ],
    'USER_GROUP_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group search fail'
    ],
    'USER_GROUP_ADMIN_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group admin search fail'
    ],
    'USER_GROUP_FRONT_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front search fail'
    ],

    // Get Item
    'USER_GROUP_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group get item success'
    ],
    'USER_GROUP_ADMIN_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin get item success'
    ],
    'USER_GROUP_FRONT_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front get item success'
    ],
    'USER_GROUP_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User group get item fail'
    ],
    'USER_GROUP_ADMIN_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User group get item search fail'
    ],
    'USER_GROUP_FRONT_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User group front get item fail'
    ],


    //Get tree
    'USER_GROUP_GET_TREE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group get tree success'
    ],
    'USER_GROUP_ADMIN_GET_TREE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin get tree success'
    ],
    'USER_GROUP_FRONT_GET_TREE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front get tree success'
    ],


    //Get Tree List
    'USER_GROUP_GET_TREE_LIST_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group get tree list success'
    ],
    'USER_GROUP_ADMIN_GET_TREE_LIST_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group admin get tree list success'
    ],
    'USER_GROUP_FRONT_GET_TREE_LIST_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group front get tree list success'
    ],



    // ----- User Group Map -----
    // Create
    'USER_GROUP_MAP_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map create success'
    ],
    'USER_GROUP_MAP_ADMIN_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin create success'
    ],
    'USER_GROUP_MAP_FRONT_CREATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front create success'
    ],
    'USER_GROUP_MAP_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map create fail'
    ],
    'USER_GROUP_MAP_ADMIN_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin create fail'
    ],
    'USER_GROUP_MAP_FRONT_CREATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front create fail'
    ],



    // Update
    'USER_GROUP_MAP_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map update success'
    ],
    'USER_GROUP_MAP_ADMIN_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin update success'
    ],
    'USER_GROUP_MAP_FRONT_UPDATE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front update success'
    ],

    'USER_GROUP_MAP_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map update fail'
    ],
    'USER_GROUP_MAP_ADMIN_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin update fail'
    ],
    'USER_GROUP_MAP_FRONT_UPDATE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front update fail'
    ],


    // Trash
    'USER_GROUP_MAP_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map trash success'
    ],
    'USER_GROUP_MAP_ADMIN_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin trash success'
    ],
    'USER_GROUP_MAP_FRONT_TRASH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front trash success'
    ],

    'USER_GROUP_MAP_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map trash fail'
    ],
    'USER_GROUP_MAP_ADMIN_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin trash fail'
    ],
    'USER_GROUP_MAP_FRONT_TRASH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front trash fail'
    ],



    // Delete
    'USER_GROUP_MAP_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map delete success'
    ],
    'USER_GROUP_MAP_ADMIN_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin delete success'
    ],
    'USER_GROUP_MAP_FRONT_DELETE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front delete success'
    ],


    'USER_GROUP_MAP_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map delete fail'
    ],
    'USER_GROUP_MAP_ADMIN_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin delete fail'
    ],
    'USER_GROUP_MAP_FRONT_DELETE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front delete fail'
    ],


    // Publish
    'USER_GROUP_MAP_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map publish success'
    ],
    'USER_GROUP_MAP_ADMIN_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin publish success'
    ],
    'USER_GROUP_MAP_FRONT_PUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front publish success'
    ],

    'USER_GROUP_MAP_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map publish fail'
    ],
    'USER_GROUP_MAP_ADMIN_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin publish fail'
    ],
    'USER_GROUP_MAP_FRONT_PUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front publish fail'
    ],


    // Unpublish
    'USER_GROUP_MAP_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map unpublish success'
    ],
    'USER_GROUP_MAP_ADMIN_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin unpublish success'
    ],
    'USER_GROUP_MAP_FRONT_UNPUBLISH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front unpublish success'
    ],
    'USER_GROUP_MAP_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map unpublish fail'
    ],
    'USER_GROUP_MAP_ADMIN_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin unpublish fail'
    ],
    'USER_GROUP_MAP_FRONT_UNPUBLISH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front unpublish fail'
    ],


    // Archive
    'USER_GROUP_MAP_ARCHIVE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map archive success'
    ],
    'USER_GROUP_MAP_ADMIN_ARCHIVE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin archive success'
    ],
    'USER_GROUP_MAP_FRONT_ARCHIVE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front archive success'
    ],
    'USER_GROUP_MAP_ARCHIVE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map archive fail'
    ],
    'USER_GROUP_MAP_ADMIN_ARCHIVE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin archive fail'
    ],
    'USER_GROUP_MAP_FRONT_ARCHIVE_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front archive fail'
    ],

    //Search
    'USER_GROUP_MAP_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map search success'
    ],
    'USER_GROUP_MAP_ADMIN_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin search success'
    ],
    'USER_GROUP_MAP_FRONT_SEARCH_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front search success'
    ],
    'USER_GROUP_MAP_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map search fail'
    ],
    'USER_GROUP_MAP_ADMIN_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map admin search fail'
    ],
    'USER_GROUP_MAP_FRONT_SEARCH_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front search fail'
    ],

    // Get Item
    'USER_GROUP_MAP_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map get item success'
    ],
    'USER_GROUP_MAP_ADMIN_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin get item success'
    ],
    'USER_GROUP_MAP_FRONT_GET_ITEM_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front get item success'
    ],
    'USER_GROUP_MAP_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map get item fail'
    ],
    'USER_GROUP_MAP_ADMIN_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map get item search fail'
    ],
    'USER_GROUP_MAP_FRONT_GET_ITEM_FAIL' => [
        'code'      => 500,
        'message'   => 'User group map front get item fail'
    ],


    //Get tree
    'USER_GROUP_MAP_GET_TREE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map get tree success'
    ],
    'USER_GROUP_MAP_ADMIN_GET_TREE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map admin get tree success'
    ],
    'USER_GROUP_MAP_FRONT_GET_TREE_SUCCESS' => [
        'code'      => 200,
        'message'   => 'User group map front get tree success'
    ],

];