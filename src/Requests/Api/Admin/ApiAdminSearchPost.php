<?php

namespace DaydreamLab\User\Requests\Api\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;

class ApiAdminSearchPost extends ListRequest
{
    protected $modelName = 'Api';

    protected $apiMethod = 'searchApi';
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
            //
        ];
        return array_merge($rules, parent::rules());
    }
}
