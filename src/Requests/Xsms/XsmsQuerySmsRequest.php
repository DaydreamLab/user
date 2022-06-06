<?php

namespace DaydreamLab\User\Requests\Xsms;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class XsmsQuerySmsRequest extends AdminRequest
{
    protected $modelName = 'Xsms';

    protected $apiMethod = 'querySms';

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
            'TaskID'    => 'required|string',
            'GetMode'   => ['required', Rule::in([0, 1])]
        ];

        return array_merge(parent::rules(), $rules);
    }
}
