<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserAdminStorePost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'storeUser';

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
        return [
            'id'                    => 'nullable|integer',
            'email'                 => 'required|email',
            'first_name'            => 'nullable|string',
            'last_name'             => 'nullable|string',
            'user_name'             => 'required|string',
            'nickname'              => 'nullable|string',
            'gender'                => 'nullable|string',
            'image'                 => 'nullable|string',
            'birthday'              => 'nullable|date',
            'phone_code'            => 'nullable|string',
            'phone'                 => 'nullable|string',
            'school'                => 'nullable|string',
            'job'                   => 'nullable|string',
            'country'               => 'nullable|string',
            'state'                 => 'nullable|string',
            'city'                  => 'nullable|string',
            'district'              => 'nullable|string',
            'address'               => 'nullable|string',
            'zipcode'               => 'nullable|string',
            'timezone'              => 'nullable|string',
            'locale'                => 'nullable|string',
            'group_ids'             => 'required|array',
            'group_ids.*'           => 'required|integer',
            'block'                 => [
                'nullable',
                Rule::in([0,1])
            ],
            'reset_password'        => [
                'nullable',
                Rule::in([0,1])
            ],
            'activation'            => [
                'nullable',
                Rule::in([0,1])
            ],
            'password'              => 'nullable|string|min:8|max:16',
            'password_confirmation' => 'nullable|same:password',
        ];
    }


    public function validated()
    {
        $validated = parent::validated();
        $validated->forget('password_confirmation');

        $groupIds = UserGroup::all()->map(function ($group) {
            return $group->id;
        })->all();

        $validGroupIds =  array_intersect($validated->get('group_ids'), $groupIds);
        if (!count($validGroupIds)) {
            throw new HttpResponseException(ResponseHelper::genResponse(
               Str::upper(Str::snake('InvalidInput')),
               ['group_ids' => $validated->get('group_ids')],
               null,
               null
            ));
        }

        return $validated;
    }
}
