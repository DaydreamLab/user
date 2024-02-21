<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserGetItemRequest;

class UserTagAdminGetItemRequest extends UserGetItemRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'getUserTag';
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

        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        return $validated;
    }
}
