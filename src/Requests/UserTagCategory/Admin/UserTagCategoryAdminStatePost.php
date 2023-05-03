<?php

namespace DaydreamLab\User\Requests\UserTagCategory\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserTagCategoryAdminStatePost extends AdminRequest
{
    protected $modelName = 'UserTagCategory';

    protected $apiMethod = 'updateUserTagCategoryState';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'ids'       => 'required|array',
            'ids.*'     => 'required|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }
}
