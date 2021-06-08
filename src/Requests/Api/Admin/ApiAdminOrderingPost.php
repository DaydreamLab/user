<?php

namespace DaydreamLab\User\Requests\Api\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class ApiAdminOrderingPost extends AdminRequest
{
    protected $modelName = 'Api';

    protected $apiMethod = 'orderingApi';
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
            'id'       => 'required|integer',
            'ordering' => 'required|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }
}
