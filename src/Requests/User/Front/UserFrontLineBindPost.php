<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontLineBindPost extends AdminRequest
{
    protected $modelName = 'Line';

    protected $apiMethod = 'lineBind';
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
            'lineId' => 'required',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}