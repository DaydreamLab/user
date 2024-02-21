<?php

namespace DaydreamLab\User\Requests\UserTagCategory\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserSearchRequest;
use Illuminate\Validation\Rule;

class UserTagCategoryAdminSearchRequest extends UserSearchRequest
{
    protected $modelName = 'UserTagCategory';

    protected $apiMethod = 'searchUserTagCategory';
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
            'state' => ['nullable', Rule::in([0,1,-2])]
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $q = $validated->get('q');
        $validated->put('q', $q->whereIn('state', [0,1])->orderBy('_lft', 'asc')->where('title', '!=', 'ROOT'));

        return $validated;
    }
}
