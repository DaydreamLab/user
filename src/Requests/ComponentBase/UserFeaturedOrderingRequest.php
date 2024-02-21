<?php

namespace DaydreamLab\User\Requests\ComponentBase;

use DaydreamLab\JJAJ\Requests\BaseFeaturedOrderingRequest;

abstract class UserFeaturedOrderingRequest extends BaseFeaturedOrderingRequest
{
    protected $package = 'User';

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
            //
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        return $validated;
    }
}
