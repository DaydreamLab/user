<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserFrontLineBindPost extends AdminRequest
{
    protected $modelName = 'Line';

    protected $apiMethod = 'lineBind';

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
            'userId'    => 'required|string',
            'platform'  => ['required', Rule::in(['LINE', 'FACEBOOK'])],
            'pId'    => 'required|string',
        ];

        return array_merge(parent::rules(), $rules);
    }
}
