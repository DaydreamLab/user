<?php

namespace DaydreamLab\User\Requests\NotificationTemplate;

use DaydreamLab\User\Requests\ComponentBase\UserStoreRequest;

class NotificationTemplateStoreRequest extends UserStoreRequest
{
    protected $modelName = 'NotificationTemplate';

    protected $apiMethod = 'storeNotificationTemplate';
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
            'id'    => 'nullable|integer',
            'type' => 'required|string',
            'subject'   => 'required|string',
            'content'   => 'required|string',
            'contentHtml'   => 'required|string'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $validated->put('channelType', 'default');
        $validated->put('category', 'message');

        return $validated;
    }
}
