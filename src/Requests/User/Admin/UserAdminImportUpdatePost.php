<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserAdminImportUpdatePost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'importUpdate';

    protected $needAuth = false;
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
            'file'  => 'required',
        ];
        return array_merge($rules, parent::rules());
    }
}
