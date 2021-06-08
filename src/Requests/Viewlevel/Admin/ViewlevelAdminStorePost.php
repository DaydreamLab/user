<?php

namespace DaydreamLab\User\Requests\Viewlevel\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class ViewlevelAdminStorePost extends AdminRequest
{
    protected $modelName = 'Viewlevel';

    protected $apiMethod = 'storeViewlevel';
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
            'id'            => 'nullable|integer',
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'ordering'      => 'nullable|integer',
            'groupIds'      => 'nullable|array',
            'groupIds.*'    => 'nullable|integer',
            'ordering'      => 'nullable|integer|gte:0'
        ];
        return array_merge(parent::rules(), $rules);
    }
}
