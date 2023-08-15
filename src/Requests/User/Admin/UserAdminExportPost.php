<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserAdminExportPost extends ListRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'exportUser';

    protected $searchKeys = ['email', 'name', 'mobilePhone'];

    protected $needAuth = false;
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
            'parent_group'  => 'nullable|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $q = $validated->pull('q');
        if ($validated->get('nonePhone')) {
            $q->where('activateToken', 'importNonePhoneUser')
                ->whereRaw("mobilePhone REGEXP '[^0-9]'");
        } elseif ($validated->get('nonePhone') === 0) {
            $q->where('activateToken', '!=', 'importNonePhoneUser')
                ->whereRaw("mobilePhone REGEXP '[0-9]'");
        }
        $validated->put('q', $q);
        $validated->forget('nonePhone');

        return $validated;
    }
}
