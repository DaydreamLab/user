<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;

class UserFrontStorePost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'storeUser';

    protected $needAuth = false;
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
            'uuid'                  => 'nullable|uuid',
            'mobilePhoneCode'       => 'nullable|regex:/\+[0-9]+$/',
            'mobilePhone'           => 'nullable|numeric',
            'name'                  => 'nullable|string',
            'email'                 => 'required|email',
            'backupEmail'           => 'nullable|email',
            'company'               => 'required|array',
            'company.name'          => 'required|string',
            'company.vat'           => ['nullable', 'numeric', new TaiwanUnifiedBusinessNumber()],
            'company.phoneCode'     => 'nullable|numeric',
            'company.phone'         => 'required|numeric',
            'company.extNumber'     => 'nullable|numeric',
            'company.email'         => 'required|email',
            'company.department'    => 'required|string',
            'company.jobTitle'      => 'required|string',
            'company.industry'      => 'nullable|string',
            'company.scale'         => 'nullable|string',
            'company.purchaseRole'  => 'nullable|string',
            'company.interestedIssue'   => 'nullable|array',
            'company.interestedIssue.*' => 'nullable|string',
            'company.issueOther'    => 'nullable|string',
            #todo: 電子報
            'newsletterCategoriesAlias'     => 'nullable|array',
            'newsletterCategoriesAlias.*'   => 'nullable|string',
            'lineId'                => 'nullable|string'
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
