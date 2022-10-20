<?php

namespace DaydreamLab\User\Requests\User\Admin;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Helpers\RequestHelper;
use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserAdminCrmSearchPost extends ListRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'searchCrmUser';

    protected $searchKeys = ['email', 'name', 'mobilePhone'];
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
            # 基本資料
            'lineBind'  => ['nullable', Rule::in([0, 1])],
            #todo 'source'
            'createdAtFrom' => 'nullable|date_format:Y-m-d',
            'createdAtTo' => 'nullable|date_format:Y-m-d',
            'lastLoginFrom' => 'nullable|date_format:Y-m-d',
            'lastLoginTo' => 'nullable|date_format:Y-m-d',
            'userGroup'   => ['nullable', Rule::in(['一般會員', '經銷會員'])],
            'userTags'  => 'nullable|array',
            'userTags.*'    => ['required', Rule::in([
                '一般會員',
                '經銷會員',
                '原廠',
                '競爭廠商',
                '零壹員工'
            ])],
            'block' => ['nullable', Rule::in([0, 1])],
            'lastUpdateFrom' => 'nullable|date_format:Y-m-d',
            'lastUpdateTo' => 'nullable|date_format:Y-m-d',
            #todo: 訂閱狀態
            'purchaseRoles'  => 'nullable|array',
            'purchaseRoles.*' => 'required|string',
            'jobTypes' => 'nullable|array',
            'jobTypes.*' => 'required|string',
            'jobCategories' => 'nullable|array',
            'jobCategories.*' => 'required|string',
            'interestedIssues' => 'nullable|array',
            'interestedIssues.*' => 'required|string',
            # 經銷公司
            #todo:公司地址
            'scale'  => 'nullable|string',
            'industry' => 'nullable|array',
            'industry.*' => ['required', 'string'],
            'companyStatus' => ['nullable', Rule::in([EnumHelper::COMPANY_APPROVED, EnumHelper::COMPANY_PENDING])],
            # 活動課程
            'eventTitle' => 'nullable|string',
            'eventStartDate'    => 'nullable|date_format:Y-m-d',
            'eventEndDate'    => 'nullable|date_format:Y-m-d',
            'eventCategory' => ['nullable', Rule::in(['課程', '活動'])],
            'eventType' => ['nullable', Rule::in(['physical', 'online'])],
            'canRegisterGroup' => ['nullable', Rule::in([6, 7])],
            'eventBrands' => 'nullable|array',
            'eventBrands.*' => 'required|string',
            'eventDateType'  => ['nullable', Rule::in(['single', 'multiple', 'series'])],
            'registrationType'  => ['nullable', Rule::in(['partial', 'impartial'])],
            # todo: 是否備取
            'regStatus' => ['nullable', Rule::in(['CONFIRMED', 'CANCELED'])],
            'replyQuestionnaire' => ['nullable', Rule::in([0, 1])],
            'participationTimes' => 'nullable|integer',
            'cancelTimes' => 'nullable|integer',
            'noshowTimes' => 'nullable|integer',
            #上課券
            'couponGroupType'    =>  ['nullable', Rule::in(['normal', 'bulk'])],
            'couponGroupUserGroup'    =>  ['nullable', Rule::in([6, 7])],
            'couponTimesForm' => 'nullable|integer',
            'couponTimesTo' => 'nullable|integer'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $q = $validated->get('q');

        if ($validated->get('lineBind')) {
            $q->has('line');
        }

        $createdAtFrom = $validated->get('createdAtFrom');
        $createdAtTo = $validated->get('createdAtTo');
        if ($createdAtFrom || $createdAtTo) {
            if ($createdAtFrom) {
                $q->where('created_at', '>=', RequestHelper::toSystemTime($createdAtFrom));
            }
            if ($createdAtTo) {
                $q->where('created_at', '<=', RequestHelper::toSystemTime($createdAtTo));
            }
        }

        $lastLoginFrom = $validated->get('lastLoginFrom');
        $lastLoginTo = $validated->get('lastLoginTo');
        if ($lastLoginFrom || $lastLoginTo) {
            if ($lastLoginFrom) {
                $q->where('lastLogin', '>=', RequestHelper::toSystemTime($lastLoginFrom));
            }
            if ($lastLoginTo) {
                $q->where('lastLogin', '<=', RequestHelper::toSystemTime($lastLoginTo));
            }
        }

        $userGroup = $validated->get('userGroup');
        if ($userGroup) {
            $q->whereHas('groups', function ($q) use ($userGroup) {
                $q->where('users_groups.title', $userGroup);
            });
        }

        $userTags = $validated->get('userTags');
        if (count($userTags)) {
            $q->has('company.company')
                ->whereHas('company.company.category', function ($q) use ($userTags) {
                    $q->whereIn('title', $userTags);
                });
        }

        $block = $validated->get('block');
        if ($block === 0 || $block === 1) {
            $q->where('block', $block);
        }

        $q->whereHas('company', function ($q) use ($validated) {
            if ($lastUpdateFrom = $validated->get('lastUpdateFrom')) {
                $q->where('users_companies.lastUpdate', '>=', RequestHelper::toSystemTime($lastUpdateFrom));
            }
            if ($lastUpdateTo = $validated->get('lastUpdateTo')) {
                $q->where('users_companies.lastUpdate', '<=', RequestHelper::toSystemTime($lastUpdateTo));
            }
            if (count($purchaseRoles = ($validated->get('purchaseRoles') ?: []))) {
                $q->whereIn('purchaseRole', $purchaseRoles);
            }
            if (count($jobTypes = ($validated->get('jobTypes') ?: []))) {
                $q->whereIn('jobType', $jobTypes);
            }
            if (count($jobCategories = $validated->get('jobCategories'))) {
                $q->whereIn('jobCategories', $jobCategories);
            }
            foreach ($validated->get('interestedIssues') ?: [] as $issue) {
                $q->where('interestedIssue', 'like', "%{$issue}%");
            }
        });

        $scale = $validated->get('scale');
        $industry = $validated->get('industry') ?: [];
        $companyStatus = $validated->get('companyStatus');
        if ($scale || count($industry) || $companyStatus) {
            $q->whereHas('company.company', function ($q) use ($scale, $industry, $companyStatus) {
                if ($scale) {
                    $q->where('companies.scale', $scale);
                }
                if (count($industry)) {
                    $q->whereIn('industry', $industry);
                }
                if ($companyStatus) {
                    $q->where('status', $companyStatus);
                }
            });
        }

        $q->whereHas('orders', function ($q) use ($validated) {
            $q->whereHas('event', function ($q) use ($validated) {
                if ($eventTitle = $validated->get('eventTitle')) {
                    $q->where('events.title', 'like', "%{$eventTitle}%");
                }
            });
        });

        return $validated;
    }
}
