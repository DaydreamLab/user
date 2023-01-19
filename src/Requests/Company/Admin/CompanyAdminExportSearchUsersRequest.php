<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Company\CompanyCategory;
use Illuminate\Validation\Rule;

class CompanyAdminExportSearchUsersRequest extends ListRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'exportCompanyMembers';

    protected $needAuth = true;

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
            'search' => 'nullable|string',
            'updateStatus' => ['nullable', Rule::in([EnumHelper::WAIT_UPDATE, EnumHelper::ALREADY_UPDATE])]
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $validated->put('company_id', $this->route('id'));
        $validated->put('limit', 0);
        $validated->put('paginate', 0);

        return $validated;
    }
}
