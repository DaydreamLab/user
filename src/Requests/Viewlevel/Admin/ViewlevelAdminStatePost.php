<?php

namespace DaydreamLab\User\Requests\Viewlevel\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class ViewlevelAdminStatePost extends AdminRequest
{
    protected $modelName = 'Viewlevel';

    protected $apiMethod = 'updateViewlevelState';
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
        return array_merge($rules, parent::rules());
    }
}
