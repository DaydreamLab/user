<?php

namespace DaydreamLab\User\Requests\Truetel;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class TruetelQuerySmsRequest extends AdminRequest
{
    protected $modelName = 'Truetel';

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
            'phone'    => 'required|string',
            'messageIds'   => 'required|array'
        ];

        return array_merge(parent::rules(), $rules);
    }
}
