<?php

namespace DaydreamLab\User\Requests\Asset\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class
AssetAdminStorePost extends AdminRequest
{
    protected $modelName = 'Asset';

    protected $apiMethod = 'storeAsset';
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
            'id'        => 'nullable|integer',
            'parent_id' => 'nullable|integer',
            'parantId'  => 'nullable|integer',
            'title'     => 'nullable|string',
            'path'      => 'nullable|string',
            'component' => 'nullable|string',
            'model'     => 'nullable|string',
            'type'      => 'required|string',
            'icon'      => 'nullable|string',
            'state'     => [
                'required',
                'integer',
                Rule::in([0,1,-2])
            ],
            'params'    => 'nullable|string',
            'ordering'  => 'nullable|integer',
            'redirect'  => 'nullable|string',
            'showNav'   => 'integer|between:0,1',
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function validated()
    {
        $validated = parent::validated();
        if ($validated->get('parentId')) {
            $validated->put('parent_id', $validated->get('parentId'));
        }

        $validated->forget('parentId');

        return $validated;
    }
}
