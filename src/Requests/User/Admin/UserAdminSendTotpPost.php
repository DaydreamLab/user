<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserAdminSendTotpPost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'sendTotp';
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
            'ids'    => 'required|array',
            'ids.*'     => 'nullable|integer',
        ];

        return array_merge($rules, parent::rules());
    }
}
