<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;
use Illuminate\Validation\Rule;

class UserTagAdminEditUsersRequest extends UserStoreRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'editUserTagUsers';
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
            'addIds' => 'required|array',
            'addIds.*' => 'nullable|integer',
            'deleteIds' => 'required|array',
            'deleteIds.*' => 'nullable|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated->put('id', $this->route('id'));

        return $validated;
    }
}
