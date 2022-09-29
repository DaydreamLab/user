<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontDealerValidateRequest extends AdminRequest
{
    protected $package = 'User';

    protected $modelName = 'User';

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
        return [];
    }


    public function validated()
    {
        $validated = parent::validated();
        $validated->put('token', $this->route('token'));
        $validated->put('user', $this->user());

        return $validated;
    }
}
