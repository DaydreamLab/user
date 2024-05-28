<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

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
    public function rules()
    {
        $rules = [
            'lineId' => 'required',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        return $validated;
    }

}
