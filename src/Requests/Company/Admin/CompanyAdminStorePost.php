<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class CompanyAdminStorePost extends AdminRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'storeCompany';
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
            'categoryId'    => 'required|integer',
            'name'          => 'required|string',
            'vat'           => 'nullable|string',
            'domain'        => 'required|string',
            'logo'          => 'nullable|string',
            'country'       => 'nullable|string',
            'state'         => 'nullable|string',
            'city'          => 'nullable|string',
            'district'      => 'nullable|string',
            'address'       => 'nullable|string',
            'zipcode'       => 'nullable|string',
            'introtext'     => 'nullable|string',
            'description'   => 'nullable|string',
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        $validated->put('state_', $validated->get('state'));
        $validated->put('category_id', $validated->get('categoryId'));
        $validated->forget(['state', 'categoryId']);

        return $validated;
    }
}
