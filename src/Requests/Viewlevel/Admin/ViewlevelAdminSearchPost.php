<?php

namespace DaydreamLab\User\Requests\Viewlevel\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use Illuminate\Validation\Rule;

class ViewlevelAdminSearchPost extends ListRequest
{
    protected $modelName = 'Viewlevel';

    protected $apiMethod = 'searchViewlevel';
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
            'title' => 'nullable|string',
            'state'     => [
                'nullable',
                'integer',
                Rule::in([0,1,-2])
            ]
        ];
        return array_merge(parent::rules(), $rules);
    }
}
