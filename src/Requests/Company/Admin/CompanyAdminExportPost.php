<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Models\Company\CompanyCategory;
use Illuminate\Validation\Rule;

class CompanyAdminExportPost extends ListRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'exportCompany';

    protected $searchKeys = ['name', 'vat', 'domain'];

    protected $needAuth = false;

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

        if ($validated->get('company_category')) {
            $validated->put('category_id', $validated->get('company_category'));
            $validated->forget('company_category');
        }

//        $validated->put('paginate', 0);
//        $validated->put('limit', 0);
        $q = $validated->get('q');
        $q->with('userCompanies');
        $validated->put('q', $q);

        return $validated;
    }
}
