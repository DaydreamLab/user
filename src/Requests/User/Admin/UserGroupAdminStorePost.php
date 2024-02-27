<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Helpers\InputHelper;
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
            'page'                          => 'nullable|array',
            'page.*'                        => 'nullable|array',
            'page.*.id'                     => 'nullable|integer',
            'page.*.assets'                 => 'nullable|array',
            'page.*.assets.*.id'              => 'nullable|integer',
            'page.*.assets.*.apis'            => 'nullable|array',
//            'page.*.assets.apis.*.id'       => 'nullable|integer',
//            //'page.*.assets.apis.*.hidden'   => 'nullable|integer',
//            //'page.*.assets.apis.*.disabled' => 'nullable|integer',
//            'page.*.assets.apis.*.checked'  => 'nullable|integer'

        ];
        return array_merge(parent::rules(), $rules);
    }
}
