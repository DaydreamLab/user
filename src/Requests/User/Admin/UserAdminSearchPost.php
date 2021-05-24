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
            'groups'    => 'nullable|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $q = $validated->get('q');
        if ($groups = $validated->get('groups')) {
            if (is_array($groups)) {
                $q->whereHas('groups', function ($q) use ($groups) {
                    $q->whereIn('id', $groups);
                });
            } else {
                $q->whereHas('groups', function ($q) use ($groups) {
                    $q->where('id', $groups);
                });
            }
        }
        $validated->put('q', $q);
        $validated->forget(['groups']);

        return $validated;
    }
}
