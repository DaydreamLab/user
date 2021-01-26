<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserGroupAdminStorePost extends AdminRequest
{
    protected $apiMethod = 'storeUserGroup';

    protected $modelName = 'UserGroup';

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
            'id'            => 'nullable|integer',
            'parent_id'     => 'nullable|integer',
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'redirect'      => 'nullable|string',
            'api_ids'       => 'required|array',
            'api_ids.*'     => 'nullable|integer',
            'asset_ids'     => 'required|array',
            'asset_ids.*'   => 'nullable|integer',
        ];
        return array_merge($rules, parent::rules());
    }
}
