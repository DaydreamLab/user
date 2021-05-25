<?php

namespace DaydreamLab\User\Requests\Viewlevel\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class ViewlevelAdminGetItem extends AdminRequest
{
    protected $modelName = 'Viewlevel';

    protected $apiMethod = 'getViewlevel';
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
        ];
        return array_merge(parent::rules(), $rules);
    }
}
