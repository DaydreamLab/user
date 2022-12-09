<?php

namespace DaydreamLab\User\Requests\NotificationTemplate;

use DaydreamLab\User\Requests\ComponentBase\UserGetItemRequest;

class NotificationTemplateGetItemRequest extends UserGetItemRequest
{
    protected $modelName = 'NotificationTemplate';

    protected $apiMethod = 'getItem';
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
            //
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
