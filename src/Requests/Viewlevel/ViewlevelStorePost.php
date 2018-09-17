<?php

namespace DaydreamLab\User\Requests\Viewlevel;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class ViewlevelStorePost extends AdminRequest
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
            'id'            => 'nullable|integer',
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'rules'         => 'required|array',
            'rules.*'       => 'nullable|integer',
        ];
    }
}
