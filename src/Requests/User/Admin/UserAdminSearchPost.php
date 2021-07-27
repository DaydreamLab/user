<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use Illuminate\Validation\Rule;

class UserAdminSearchPost extends ListRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'searchUser';

    protected $searchKeys = ['email', 'name'];
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
            'email'     => 'nullable|string',
            'block'     => [
                'nullable',
                'integer',
                Rule::in([0,1,-2])
            ],
            'activation'    => [
                'nullable',
                'integer',
                Rule::in([0,1])
            ],
            'search'    => 'nullable|string',
            'user_group'    => 'nullable|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $q = $validated->get('q');
        if ($groups = $validated->get('user_group')) {
            if (is_array($groups)) {
                $q->whereHas('groups', function ($q) use ($groups) {
                    $q->whereIn('users_groups_maps.group_id', $groups);
                });
            } else {
                $q->whereHas('groups', function ($q) use ($groups) {
                    $q->where('users_groups_maps.group_id', $groups);
                });
            }
        }
        $validated->put('q', $q);
        $validated->forget(['user_group']);

        return $validated;
    }
}
