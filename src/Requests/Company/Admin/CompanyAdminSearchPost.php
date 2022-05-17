<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Models\Company\CompanyCategory;
use Illuminate\Validation\Rule;

class CompanyAdminSearchPost extends ListRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'searchCompany';

    protected $searchKeys = ['name', 'vat', 'domain'];

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
            'title' => 'nullable|string',
            'state'     => [
                'nullable',
                'integer',
                Rule::in([0,1,-2])
            ],
            'company_category' => 'nullable|integer'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        if ( $validated->get('company_category') ) {
            $validated->put('category_id', $validated->get('company_category'));
            $validated->forget('company_category');
        } else {
            $category_ids = CompanyCategory::query()->where('title', '!=', '一般')->get()->pluck('id');
            $q = $validated->get('q');
            $q->whereIn('category_id', $category_ids);
        }

        return $validated;
    }
}
