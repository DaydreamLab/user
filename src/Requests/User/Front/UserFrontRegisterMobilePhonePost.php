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
            'company'           => 'required|array',
            'company.name'      => 'required|string',
            'company.email'     => 'required|email',
            'company.vat'       => ['required', 'numeric', new TaiwanUnifiedBusinessNumber()],
            'company.phoneCode' => 'required|numeric',
            'company.phone'     => 'required|numeric',
            'company.extNumber' => 'nullable|numeric',
            'company.department'=> 'required|string',
            'company.jobTitle'  => 'required|string',
            'company.industry'      => 'required|string',
            'company.scale'         => 'nullable|string',
            'company.purchaseRole'  => 'nullable|string',
            'company.interestedIssue'   => 'nullable|array',
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
