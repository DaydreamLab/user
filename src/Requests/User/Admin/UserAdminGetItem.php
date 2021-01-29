<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserAdminGetItem extends AdminRequest
{
    protected $modelName = 'User';

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
            'ids'       => 'required|array',
            'ids.*'     => 'nullable|integer',
            'block'     => [
                'required',
                Rule::in(0,1)
            ],
        ];
        return array_merge($rules, parent::rules());
    }
}