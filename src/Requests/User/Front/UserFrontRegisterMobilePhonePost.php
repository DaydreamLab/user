<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;

class UserFrontRegisterMobilePhonePost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'register';

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
            'uuid'              => 'required|uuid',
            'name'              => 'required|string',
            'email'             => 'required|email',
            'backupEmail'       => 'nullable|email',
            'verificationCode'  => 'required|numeric',
            'company'           => 'nullable|array',
            'company.name'      => 'nullable|string',
            'company.email'     => 'nullable|email',
            'company.vat'       => ['required', 'numeric', new TaiwanUnifiedBusinessNumber()],
            'company.phoneCode' => 'nullable|numeric',
            'company.phone'     => 'nullable|numeric',
            'company.extNumber' => 'nullable|numeric',
            'company.department'=> 'nullable|string',
            'company.jobTitle'  => 'nullable|string',
            'company.industry'      => 'nullable|string',
            'company.scale'         => 'nullable|string',
            'company.purchaseRole'  => 'nullable|string',
            'company.interestedIssue'   => 'nullable|array',
            'company.interestedIssue.*' => 'nullable|string',
            'company.issueOther'    => 'nullable|string',
            'newsletterCategoriesAlias'     => 'nullable|array',
            'newsletterCategoriesAlias.*'   => 'nullable|string'
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
