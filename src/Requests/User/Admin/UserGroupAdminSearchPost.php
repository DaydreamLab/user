<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;

class UserGroupAdminSearchPost extends ListRequest
{
    protected $apiMethod = 'searchUserGroup';

    protected $modelName = 'UserGroup';
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
            'group_id'  => 'nullable|integer'
        ];
        return array_merge($rules, parent::rules());
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        if ( $group_id = $validated->get('group_id') ) {
            $validated['q'] = $this->q->where('parent_id', $group_id);
            $validated->forget('group_id');
        }

        return $validated;
    }
}
