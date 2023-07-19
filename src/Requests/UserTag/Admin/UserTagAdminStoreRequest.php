<?php

namespace DaydreamLab\User\Requests\UserTag\Admin;

use DaydreamLab\JJAJ\Helpers\RequestHelper;
use DaydreamLab\User\Helpers\CompanyRequestHelper;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserTagAdminStoreRequest extends UserStoreRequest
{
    protected $modelName = 'UserTag';

    protected $apiMethod = 'storeUserTag';
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
            'id' => 'nullable|integer',
            'title' => 'required|string',
            'categoryId' => 'nullable|integer',
            'type'  => ['required', Rule::in(['auto', 'manual'])],
            'rules' => 'required|array',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $validated->put('originalRules', $validated->get('rules'));
        # 額外處理公司銷售記錄
        $rules = $this->handleRules($validated->get('rules'));
        $rules['companyOrder'] = CompanyRequestHelper::handleCompanyOrder($rules['companyOrder']);
        $validated->put('rules', $rules) ;

        return $validated;
    }


    public function handleRules($rules)
    {
        $keys = ['basic', 'event', 'menu', 'company'];
        foreach ($keys as $key) {
            $data = $rules[$key];
            $checkKeysType = 'USERTAG_' . Str::upper($key) . '_CHECK_KEYS';
            foreach (EnumHelper::constant($checkKeysType) as $checkKey => $function) {
                if (isset($data[$checkKey])) {
                    $data[$checkKey] = RequestHelper::toSystemTime($data[$checkKey], 'Asia/Taipei', $function);
                }
            }
            $rules[$key] = $data;
        }
        return $rules;
    }
}
