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
            'ordering'      => 'nullable|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }

    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
