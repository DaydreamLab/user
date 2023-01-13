<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Company\CompanyCategory;
use Illuminate\Validation\Rule;

class CompanyAdminSearchUsersRequest extends ListRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'searchCompanyMembers';

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

        $q = $validated->get('q');
        $updateStatus = $validated->pull('updateStatus');
        $q->whereHas('company', function ($q) use ($updateStatus) {
            $q->where('users_companies.company_id', $this->route('id'));
            if ($updateStatus && $updateStatus == EnumHelper::WAIT_UPDATE) {
                $q->where(function ($q) {
                    $q->where(
                        'users_companies.lastUpdate',
                        '<=',
                        now()->subDays(config('daydreamlab.user.userCompanyUpdateInterval', 120))->toDateTimeString()
                    )->orWhereNull('users_companies.lastUpdate');
                });
            } elseif ($updateStatus && $updateStatus == EnumHelper::ALREADY_UPDATE) {
                $q->where(
                    'users_companies.lastUpdate',
                    '>',
                    now()->subDays(config('daydreamlab.user.userCompanyUpdateInterval', 120))->toDateTimeString()
                );
            }
        });

        return $validated;
    }
}
