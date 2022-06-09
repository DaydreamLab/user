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
        $groupFilterResult = $mapQuery->get()->unique('user_id') ?: collect();
        $groupFilterUserIds = $groupFilterResult->pluck('user_id');

        $q = $validated->get('q');

        $searchFilterUserIds = collect();
        if ($search = $this->get('search')) {
            $searchFilterUserIds = DB::table('users_companies')
                ->select('user_id')
                ->where('name', 'like', "%$search%")
                ->get()
                ->pluck('user_id')
                ->values()
                ?: collect();
        }

        if ($groupFilterUserIds->count()) {
            $q->whereIn('id', $groupFilterUserIds->all());
            if ($searchFilterUserIds->count()) {
                $searchKeys = $validated->get('searchKeys');
                $intersect = $groupFilterUserIds->intersect($searchFilterUserIds)->all();
                $searchKeys[] = function ($q) use ($intersect){
                    $q->whereIn('id', $intersect);
                };
                $validated->put('searchKeys', $searchKeys);
            } else {
                $q->whereIn('id', $groupFilterUserIds->all());
            }
        } else {
            if ($searchFilterUserIds->count()) {
                $q->whereIn('id', $searchFilterUserIds->all());
            }
        }

        $validated->put('q', $q);
        $validated->forget(['user_group']);

        return $validated;
    }
}
