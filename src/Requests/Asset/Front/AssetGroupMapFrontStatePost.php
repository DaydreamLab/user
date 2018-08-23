<?php

namespace DaydreamLab\User\Requests\Asset\Front;

use DaydreamLab\User\Requests\Asset\AssetGroupMapStatePost;

class AssetGroupMapFrontStatePost extends AssetGroupMapStatePost
{
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
        return array_merge($rules, parent::rules());
    }
}
