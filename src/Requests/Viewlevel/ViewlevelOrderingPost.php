<?php

namespace DaydreamLab\User\Requests\Viewlevel;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class ViewlevelOrderingPost extends AdminRequest
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
        return [
            'id'            => 'required|integer',
            'index_diff'    => 'required|integer',
        ];
    }
}
