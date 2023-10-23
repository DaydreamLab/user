<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserSearchRequest;
use Illuminate\Validation\Rule;

class UserTagAdminSearchRequest extends UserSearchRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'searchUserTag';
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
            'type'  => ['nullable', Rule::in(['auto', 'manual'])],
            'state' => ['nullable', Rule::in([-2, 1])],
            'categoryId' => 'nullable|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $q = $validated->get('q');
        $q->with('category', 'creator', 'activeUsers');
        $validated->put('q', $q);

        return $validated;
    }
}
