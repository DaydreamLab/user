<?php

namespace DaydreamLab\User\Requests\UserTagCategory\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;

class UserTagCategoryAdminStoreRequest extends UserStoreRequest
{
    protected $modelName = 'UserTagCategory';

    protected $apiMethod = 'storeUserTagCategory';
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
            'id' => 'nullable|integer',
            'title' => 'required|string',
            'description' => 'nullable|string'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}