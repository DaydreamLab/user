<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;
use Illuminate\Validation\Rule;

class UserTagAdminStoreRequest extends UserStoreRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'storeUserTag';
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
            'type'  => ['required', Rule::in(['auto', 'manual'])],
            'rules' => 'required|array',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
