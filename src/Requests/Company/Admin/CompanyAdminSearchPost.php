<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\Cms\Models\Item\Item;
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
            'company_category' => 'nullable|integer',
            'company_industry' => 'nullable|integer',
            'memberOperator'    => ['nullable', Rule::in(['>', '=', '<'])],
            'memberCount'   => 'nullable|integer'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $q = $validated->get('q');

        if ($validated->get('company_category')) {
            $validated->put('category_id', $validated->get('company_category'));
            $validated->forget('company_category');
        } else {
            if (!$validated->get('search')) {
                $category_ids = CompanyCategory::query()->where('state', 1)->get()->pluck('id');
                $q->whereIn('category_id', $category_ids);
            }
        }

        $validated->put('q', $q);

        return $validated;
    }
}
