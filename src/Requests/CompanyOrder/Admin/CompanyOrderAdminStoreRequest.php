<?php

namespace DaydreamLab\User\Requests\CompanyOrder\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;

class CompanyOrderAdminStoreRequest extends UserStoreRequest
{
    protected $modelName = 'CompanyOrder';

    protected $apiMethod = 'storeCompanyOrder';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'id' => 'nullable|integer',
            'userId' =>  'required|integer',
            'orderNum'  => 'required|string',
            'date'  => 'required|date_format:Y-m-d H:i:s',
//            'items' => 'nullable|array',
//            'items.*'   => 'required|array',
//            'items.*.id'   => 'nullable|integer',
//            'items.*.title'   => 'required|string',
//            'items.*.unitPrice'     => 'required|integer',
//            'items.*.qty'   => 'required|integer',
//            'deleteItems' => 'nullable|array',
//            'deleteItems.*' => 'required|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $validated->put('items', $this->handleInputItems($validated->get('items')));
        $validated->put('company_id', $this->route('companyId'));

        return $validated;
    }


    public function handleInputItems($items): array
    {
        $data = [];
        foreach ($items ?: [] as $inputItem) {
            $data[] = [
                'id' => $inputItem['id'] ?? null,
                'title' => $inputItem['title'],
                'unitPrice' => $inputItem['unitPrice'],
                'qty' => $inputItem['qty'],
            ];
        }

        return $data;
    }
}
