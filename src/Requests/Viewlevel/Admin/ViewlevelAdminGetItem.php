<?php

namespace DaydreamLab\User\Requests\Viewlevel\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class ViewlevelAdminGetItem extends AdminRequest
{
    protected $modelName = 'Viewlevel';

    protected $apiMethod = 'getItem';
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
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'rules'         => 'nullable|array',
            'rules.*'       => 'nullable|integer',
        ];
        return array_merge($rules, parent::rules());
    }
}
