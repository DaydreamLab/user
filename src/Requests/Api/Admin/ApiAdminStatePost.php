<?php

namespace DaydreamLab\User\Requests\Api\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class ApiAdminStatePost extends AdminRequest
{
    protected $modelName = 'Api';

    protected $apiMethod = 'updateApiState';
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
            'ids.*'     => 'required|integer',
            'state'     => [
                'required',
                'integer',
                Rule::in([0,1,-2])
            ]
        ];
        return array_merge(parent::rules(), $rules);
    }
}
