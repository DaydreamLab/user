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

    protected $searchKeys = ['email', 'name', 'mobilePhone', 'uuid'];
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
            'nonePhone'     => ['nullable', Rule::in([0,1])],
            'search'        => 'nullable|string',
            'user_group'    => 'nullable|integer',
            'parent_group'  => 'nullable|integer',
            'companyCategoryId' => 'nullable|integer',
            # CRM
        ];
        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        $q = $validated->get('q');

        $parent_group = $validated->get('parent_group');
        $child_group = $validated->get('user_group');
        if ($parent_group || $child_group) {
            $q->whereIn('id', function ($q) use ($parent_group, $child_group) {
                $q->select('user_id')->from('users_groups_maps');
                if ($parent_group) {
                    $g = UserGroup::where('id', $parent_group)->first();
                    $c = $g->descendants;
                    if ($child_group) {
                        $q->where('users_groups_maps.group_id', $child_group);
                    } else {
                        $c = $c->where('title', '!=', '無手機名單');
                        $ids = array_merge($c->pluck('id')->all(), [$g->id]);
                        $q->whereIn('users_groups_maps.group_id', $ids);
                    }
                } else {
                    if ($child_group) {
                        is_array($child_group)
                        ? $q->whereIn('users_groups_maps.group_id', $child_group)
                        : $q->where('users_groups_maps.group_id', $child_group);
                    }
                }
            });
        }

        if ($validated->get('nonePhone')) {
            $q->where('activateToken', 'importNonePhoneUser')
                ->whereRaw("mobilePhone REGEXP '[^0-9]'");
        } elseif ($validated->get('nonePhone') === 0) {
            $q->where('activateToken', '!=', 'importNonePhoneUser')
                ->whereRaw("mobilePhone REGEXP '[0-9]'");
        }

        if ($companyCategoryId = $validated->get('companyCategoryId')) {
            $q->whereHas('company.company.category', function ($q) use ($companyCategoryId) {
                $q->where('companies_categories.id', $companyCategoryId);
            });
        }

        $validated->put('q', $q);
        $validated->forget(['parent_group', 'user_group', 'nonePhone', 'companyCategoryId']);

        return $validated;
    }
}
