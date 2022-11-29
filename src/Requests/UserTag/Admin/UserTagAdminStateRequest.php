<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\User\Requests\ComponentBase\UserStateRequest;
use Illuminate\Validation\Rule;

class UserTagAdminStateRequest extends UserStateRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'updateUserTagState';
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
        ];
        return array_merge(parent::rules(), $rules);
    }
}
