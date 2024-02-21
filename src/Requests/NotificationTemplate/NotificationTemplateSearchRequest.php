<?php

namespace DaydreamLab\User\Requests\NotificationTemplate;

use DaydreamLab\User\Requests\ComponentBase\UserSearchRequest;
use Illuminate\Validation\Rule;

class NotificationTemplateSearchRequest extends UserSearchRequest
{
    protected $modelName = 'NotificationTemplate';

    protected $apiMethod = 'searchNotificationTemplate';

    protected $searchKeys = ['type', 'subject', 'content'];
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
            'state' => ['nullable', Rule::in([1,-2])]
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated->put('q', $validated->get('q')->where('category', 'marketing'));

        return $validated;
    }
}
