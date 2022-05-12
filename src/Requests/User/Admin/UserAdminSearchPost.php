<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserAdminSearchPost extends ListRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'searchUser';

    protected $searchKeys = ['email', 'name', 'mobilePhone'];
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
            'search'        => 'nullable|string',
            'user_group'    => 'nullable|integer',
            'parent_group'  => 'nullable|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $mapQuery = DB::table('users_groups_maps');
        if ($parent = $validated->get('parent_group')) {
            $g = UserGroup::where('id', $parent)->first();
            $c = $g->descendants->pluck(['id'])->toArray();
            $ids = array_merge($c, [$g->id]);
            $mapQuery = $mapQuery->whereIn('group_id', $ids);
            if ($parent == 3) {
                $mapQuery->whereNotIn('group_id', [4,5,8,9,10,11,12,13]);
            }
        }

        $validated->forget('parent_group');

        if ($groups = $validated->get('user_group')) {
            $mapQuery = is_array($groups)
                ? $mapQuery->whereIn('group_id', $groups)
                : $mapQuery->where('group_id', $groups);
        }
        $mapResult = $mapQuery->get()->unique('user_id');

        $q = $validated->get('q');
        $q->whereIn('id', $mapResult->pluck('user_id')->all());
        $validated->put('q', $q);
        $validated->forget(['user_group']);

        return $validated;
    }
}
