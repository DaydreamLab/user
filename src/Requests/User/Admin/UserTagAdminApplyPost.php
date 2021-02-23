<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserTagAdminApplyPost extends AdminRequest
{
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
            'userIds'      => 'required|array',
            'userIds.*'    => 'required|integer',
            'addIds'         => 'nullable|array',
            'addIds.*'       => 'nullable|integer',
            'deleteIds'      => 'nullable|array',
            'deleteIds.*'    => 'nullable|integer',
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        $validated->put('addIds', $validated->get('addIds') ?:[]);
        $validated->put('deleteIds', $validated->get('deleteIds') ?:[]);

        $validated->put('getAddQuery',collect([
            'special_queries' => [
                [
                    'type'  => 'whereIn',
                    'key'   => 'id',
                    'value' => $validated->get('addIds')
                ]
            ],
            'limit'         => 0,
            'paginate'      => 0,
        ]));

        $validated->put('getDeleteQuery',collect([
            'special_queries' => [
                [
                    'type'  => 'whereIn',
                    'key'   => 'id',
                    'value' => $validated->get('deleteIds')
                ]
            ],
            'limit'         => 0,
            'paginate'      => 0,
        ]));

        return $validated;
    }

}
