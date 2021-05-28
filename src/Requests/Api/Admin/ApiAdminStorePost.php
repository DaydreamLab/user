<?php

namespace DaydreamLab\User\Requests\Api\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class ApiAdminStorePost extends AdminRequest
{
    protected $modelName = 'Api';

    protected $apiMethod = 'storeApi';
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
            'name'          => 'required|string',
            'state'         => ['required', Rule::in([0,1])],
            'method'        => 'required|string',
            'url'           => 'required|string',
            'description'   => 'nullable|string',
            'assetIds'      => 'nullable|array',
            'assetIds.*'    => 'nullable|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }
}
