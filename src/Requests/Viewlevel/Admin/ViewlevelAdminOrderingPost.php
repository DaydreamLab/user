<?php

namespace DaydreamLab\User\Requests\Viewlevel\Admin;

use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class ViewlevelAdminOrderingPost extends AdminRequest
{
    protected $modelName = 'Viewlevel';

    protected $apiMethod = 'orderingViewlevel';
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
            'id'            => 'required|integer',
            'index_diff'    => 'nullable|integer',
            'indexDiff'     => 'nullable|integer',
            'order'         => ['nullable', Rule::in(['asc', 'desc'])]
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function validated()
    {
        $validated = parent::validated();

        if (InputHelper::null($validated, 'order')) {
            $validated->put('order', 'asc');
        }

        return $validated;
    }
}
