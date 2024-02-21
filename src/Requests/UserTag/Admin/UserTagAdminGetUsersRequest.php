<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserSearchRequest;

class UserTagAdminGetUsersRequest extends UserSearchRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'getUserTagUsers';

    protected $searchKeys = [];
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
        $validated->put('id', $this->route('id'));

        return $validated;
    }
}
