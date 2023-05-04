<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;

class UserTagAdminBatchRequest extends UserStoreRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'batchUserTag';
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
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
            'categoryId' => 'required|integer'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
