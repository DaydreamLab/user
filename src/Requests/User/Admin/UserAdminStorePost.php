<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
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
            'firstName'             => 'required|string',
            'lastName'              => 'required|string',
            'nickname'              => 'nullable|string',
            'gender'                => 'nullable|string',
            'image'                 => 'nullable|string',
            'birthday'              => 'nullable|date',
            'phoneCode'             => 'nullable|string',
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
            'groupIds'              => 'required|array',
            'groupIds.*'            => 'required|integer',
            'block'                 => [
                'nullable',
                Rule::in([0,1])
            ],
            'resetPassword'         => [
                'nullable',
                Rule::in([0,1])
            ],
            'activation'            => [
                'nullable',
                Rule::in([0,1])
            ],
            'password'              => 'required_without:id|nullable|string|min:8|max:16',
            'passwordConfirm'       => 'required_with:password|same:password',
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        if (!$validated->get('id')) {
            $validated->put('password', bcrypt($validated->get('password')));
        } else {
            if ($validated->get('password')) {
                $validated->put('password', bcrypt($validated->get('password')));
            } else {
                $validated->forget('password');
            }
        }

        return $validated;
    }
}
