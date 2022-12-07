<?php

namespace DaydreamLab\User\Requests\User\Admin;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Helpers\RequestHelper;
use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use DaydreamLab\Cms\Helpers\EnumHelper as CmsEnumHelper;
use DaydreamLab\Dsth\Helpers\EnumHelper as DsthEnumHelper;

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
            'basic' => 'required|array',
            /**** 基本資料****/
            'basic.userGroup'   => ['nullable', Rule::in(['一般會員', '經銷會員'])],
            # LINE綁定
            'basic.lineBind'  => ['nullable', Rule::in(['是', '否'])],
            #todo 'source'
            # 註冊日期
            'basic.createdAtFrom' => 'nullable|date_format:Y-m-d',
            'basic.createdAtTo' => 'nullable|date_format:Y-m-d',
            # 最後登入日期
            'basic.lastLoginAtFrom' => 'nullable|date_format:Y-m-d',
            'basic.lastLoginAtTo' => 'nullable|date_format:Y-m-d',
            'basic.block' => ['nullable', Rule::in(['不拘', '是', '否'])],
            'basic.lastUpdateFrom' => 'nullable|date_format:Y-m-d',
            'basic.lastUpdateTo' => 'nullable|date_format:Y-m-d',
            # 採購身分
            'basic.purchaseRoles'  => 'nullable|array',
            'basic.purchaseRoles.*' => 'required|string',
            # 公司職位
            'basic.jobTypes' => 'nullable|array',
            'basic.jobTypes.*' => 'required|string',
            # 職位
            'basic.jobCategories' => 'nullable|array',
            'basic.jobCategories.*' => 'required|string',
            # 有興趣的議題
            'basic.interestedIssues' => 'nullable|array',
            'basic.interestedIssues.*' => 'required|string',
            # 電子報訂閱狀態
            'basic.subscription' => ['nullable', Rule::in([
                CmsEnumHelper::NEWSLETTER_NONESUBSCRIBE,
                CmsEnumHelper::NEWSLETTER_SUBSCRIBE,
                CmsEnumHelper::NEWSLETTER_UNSUBSCRIBE
            ])],

            /***** 公司 *****/
            'company' => 'required|array',
            # 公司所在城市
            'company.city' => 'nullable|array',
            'company.city.*' => 'required|string',
            # 公司分類註記
            'company.categoryNotes'  => 'nullable|array',
            'company.categoryNotes.*'    => ['required', Rule::in([
                EnumHelper::COMPANY_NOTE_STAFF,
                EnumHelper::COMPANY_NOTE_COMPETITION,
                EnumHelper::COMPANY_NOTE_BLACKLIST,
                EnumHelper::COMPANY_NOTE_NONE,
                EnumHelper::COMPANY_NOTE_OBM
            ])],
            # 公司產業別
            'company.industry' => 'nullable|array',
            'company.industry.*' => ['required', 'string'],
            # 公司規模
            'company.scale'  => 'nullable|string',

            /**** 活動課程 ****/
            'event' => 'required|array',
            'event.search' => 'nullable|string',
            'event.category' => ['nullable', Rule::in(['不拘', '課程', '活動'])],
            'event.type' => ['nullable', Rule::in(['不拘', '實體', '線上'])],
            'event.canRegisterGroup' => ['nullable', Rule::in(['不拘', '一般會員', '經銷會員'])],
            'event.dateType'  => ['nullable', Rule::in(['不拘', '單天', '多天', '系列'])],
            'event.registrationType'  => ['nullable', Rule::in(['不拘', '統一報名', '依場次報名'])],
            'event.brands' => 'nullable|array',
            'event.brands.*' => 'required|string',
            'event.startDate'    => 'nullable|required_with:event.endDate|date_format:Y-m-d',
            'event.endDate'    => 'nullable|required_with:event.startDate|date_format:Y-m-d',

            # todo: 是否備取
            'order' => 'required|array',
            'order.replyQuestionnaire' => ['nullable', Rule::in(['不拘',  '是', '否'])],
            'order.regStatus' => ['nullable', Rule::in(['報名成功', '報名取消'])],
            'order.participateTimesFrom' => 'nullable|integer',
            'order.participateTimesTo' => 'nullable|integer',
            'order.cancelTimesFrom' => 'nullable|integer',
            'order.cancelTimesTo' => 'nullable|integer',
            'order.noshowTimesFrom' => 'nullable|integer',
            'order.noshowTimesTo' => 'nullable|integer',
            #上課券
            'coupon' => 'required|array',
            'coupon.type'    =>  ['nullable', Rule::in(['不拘', '一般上課券', '批次上課券'])],
            'coupon.userGroup'    =>  ['nullable', Rule::in(['不拘', '一般會員', '經銷會員'])],
            'coupon.useTimesFrom' => 'nullable|integer',
            'coupon.useTimesTo' => 'nullable|integer'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $this->handleBasicQuery($validated);
        $this->handleCompanyQuery($validated);
        $this->handleOrderEventCouponQuery($validated);

        return $validated;
    }

    public function handleBasicQuery(Collection &$validated)
    {
        $basic = $validated->get('basic');
        $q = $validated->get('q');
        # 排除 noneCustomer
        $q->where('id', '!=', 48464);

        if ($basic['userGroup']) {
            $q->whereHas('groups', function ($q) use ($basic) {
                $q->where('users_groups.title', $basic['userGroup']);
            });
        }

        if ($basic['lineBind'] == '是') {
            $q->whereHas('line');
        } elseif ($basic['lineBind'] == '否') {
            $q->whereDoesntHave('line');
        }

        if ($basic['createdAtFrom'] || $basic['createdAtTo']) {
            if ($basic['createdAtFrom']) {
                $q->where('created_at', '>=', RequestHelper::toSystemTime($basic['createdAtFrom']));
            }
            if ($basic['createdAtTo']) {
                $q->where('created_at', '<=', RequestHelper::toSystemTime($basic['createdAtTo']));
            }
        }

        if ($basic['lastLoginAtFrom'] || $basic['lastLoginAtTo']) {
            if ($basic['lastLoginAtFrom']) {
                $q->where('lastLoginAt', '>=', RequestHelper::toSystemTime($basic['lastLoginAtFrom']));
            }
            if ($basic['lastLoginAtTo']) {
                $q->where('lastLoginAt', '<=', RequestHelper::toSystemTime($basic['lastLoginAtTo']));
            }
        }

        $block = $validated->get('block');
        if (in_array($block, ['是', '否'])) {
            $q->where('block', $block);
        }

        if (
            $basic['lastUpdateFrom']
            || $basic['lastUpdateTo']
            || count($basic['purchaseRoles'] ?: [])
            || count($basic['jobTypes'] ?: [])
            || count($basic['jobCategories'] ?: [])
            || count($basic['interestedIssues'] ?: [])
        ) {
            $q->whereHas('company', function ($q) use ($basic) {
                if ($basic['lastUpdateFrom']) {
                    $q->where(
                        'users_companies.lastUpdate',
                        '>=',
                        RequestHelper::toSystemTime($basic['lastUpdateFrom'])
                    );
                }
                if ($basic['lastUpdateTo']) {
                    $q->where('users_companies.lastUpdate', '<=', RequestHelper::toSystemTime($basic['lastUpdateTo']));
                }
                if (count($basic['purchaseRoles'] ?: [])) {
                    $q->whereIn('purchaseRole', $basic['purchaseRoles']);
                }
                if (count($basic['jobTypes'] ?: [])) {
                    $q->whereIn('jobType', $basic['jobTypes']);
                }
                if (count($basic['jobCategories'] ?: [])) {
                    $q->whereIn('jobCategories', $basic['jobCategories']);
                }
                foreach ($basic['interestedIssues'] ?: [] as $issue) {
                    $q->where('interestedIssue', 'like', "%{$issue}%");
                }
            });
        }

        if ($basic['subscription'] == CmsEnumHelper::NEWSLETTER_SUBSCRIBE) {
            $q->whereHas('newsletterSubscription.newsletterCategories');
        } elseif ($basic['subscription']  == CmsEnumHelper::NEWSLETTER_NONESUBSCRIBE) {
            $q->whereDoesntHave('newsletterSubscription.newsletterCategories');
        } elseif ($basic['subscription']  == CmsEnumHelper::NEWSLETTER_UNSUBSCRIBE) {
            $q->whereHas('newsletterSubscription', function ($q) {
                $q->whereNotNull('cancelAt');
            });
        }

        $validated->put('q', $q);
    }

    public function handleCompanyQuery(&$validated)
    {
        $company = $validated->get('company');
        $q = $validated->get('q');

        if (
            count($company['city'] ?: [])
            || count($company['categoryNotes'] ?: [])
            || count($company['industry'] ?: [])
            || $company['scale']
        ) {
            $q->whereHas('company.company', function ($q) use ($company) {
                if (count($company['categoryNotes'] ?: [])) {
                    $q->whereIn('companies.categoryNote', $company['categoryNotes']);
                }

                $industry = $company['industry'] ?: [];
                if (count($industry)) {
                    $q->where(function ($q) use ($industry) {
                        foreach ($industry as $i) {
                            $q->orWhere('companies.industry', 'like', "%{$i}%");
                        }
                    });
                }

                $city = $company['city'] ?: [];
                if (count($city)) {
                    $q->where(function ($q) use ($city) {
                        foreach ($city as $c) {
                            $q->orWhere('companies.city', $c);
                        }
                    });
                }
                if ($company['scale']) {
                    $q->where('companies.scale', $company['scale']);
                }
            });
        }

        $validated->put('q', $q);
    }

    public function handleOrderEventCouponQuery(&$validated)
    {
        $order = $validated->get('order');
        $event = $validated->get('event');
        $coupon = $validated->get('coupon');

        $eventValues = [
            $event['search'],
            $event['category'],
            $event['type'],
            $event['canRegisterGroup'],
            count($event['brands'] ?: []),
            $event['dateType'],
            $event['registrationType'],
            $event['startDate'],
            $event['endDate'],
        ];

        $orderValues = [
            $order['regStatus'],
            $order['participateTimesFrom'],
            $order['participateTimesTo'],
            $order['cancelTimesFrom'],
            $order['cancelTimesTo'],
            $order['noshowTimesFrom'],
            $order['noshowTimesTo'],
        ];

        $couponValues = [
            $coupon['type'],
            $coupon['userGroup']
        ];

        $q = $validated->get('q');

        if ($order['replyQuestionnaire'] == '是') {
            $q->whereHas('questionnaire');
        } elseif ($order['replyQuestionnaire'] == '否') {
            $q->whereDoesntHave('questionnaire');
        }

        if ($this->valueOr($couponValues)) {
            $q->whereHas('couponGroups', function ($q) use ($coupon) {
                if (in_array($coupon['type'], ['一般上課券', '批次上課券']) || $coupon['userGroup']) {
                    if ($coupon['type'] == '一般上課券') {
                        $q->where('type', 'normal');
                    }
                    if ($coupon['type'] == '批次上課券') {
                        $q->where('type', 'normal');
                    }
                    if ($coupon['userGroup']) {
                        $q->whereHas('userGroups', function ($q) use ($coupon) {
                            if (in_array($coupon['userGroup'], ['一般會員', '經銷會員'])) {
                                $q->where('users_groups.title', $coupon['userGroup']);
                            }
                        });
                    }
                }
            });
        }

        if ($this->valueOr($orderValues) || $this->valueOr($eventValues)) {
            $q->whereHas('orders', function ($q) use ($order, $event, $orderValues, $eventValues) {
                if ($this->valueOr($orderValues)) {
                    $participateTimesFrom = $order['participateTimesFrom'];
                    $participateTimesTo = $order['participateTimesTo'];
                    if ($participateTimesFrom || $participateTimesTo) {
                        $q->select(['userId', DB::raw('COUNT(*) as ordersCount')])
                            ->groupBy('userId');
                        if ($participateTimesFrom) {
                            $q->having('ordersCount', '>=', $participateTimesFrom);
                        }
                        if ($participateTimesTo) {
                            $q->having('ordersCount', '<=', $participateTimesTo);
                        }
                    }
                    if (
                        $order['cancelTimesFrom']
                        || $order['cancelTimesTo']
                        || $order['noshowTimesFrom']
                        || $order['noshowTimesTo']
                    ) {
                        $q->whereHas('items', function ($q) use ($order) {
                            $cancelTimesFrom = $order['cancelTimesFrom'];
                            $cancelTimesTo = $order['cancelTimesTo'];
                            if ($cancelTimesFrom || $cancelTimesTo) {
                                $q->select(['orderId', DB::raw('COUNT(*) as cancelOrdersCount')])
                                    ->where('order_items.regStatus', DsthEnumHelper::CANCELED)
                                    ->groupBy('orderId');
                                if ($cancelTimesFrom) {
                                    $q->having('cancelOrdersCount', '>=', $cancelTimesFrom);
                                }
                                if ($cancelTimesTo) {
                                    $q->having('cancelOrdersCount', '<=', $cancelTimesTo);
                                }
                            }

                            $noshowTimesFrom = $order['noshowTimesFrom'];
                            $noshowTimesTo = $order['noshowTimesTo'];
                            if ($noshowTimesFrom || $noshowTimesTo) {
                                $q->select(['orderId', DB::raw('COUNT(*) as noshowOrdersCount')])
                                    ->where('order_items.regStatus', DsthEnumHelper::NOSHOW)
                                    ->groupBy('orderId');
                                if ($noshowTimesFrom) {
                                    $q->having('noshowOrdersCount', '>=', $noshowTimesFrom);
                                }
                                if ($noshowTimesTo) {
                                    $q->having('noshowOrdersCount', '<=', $noshowTimesTo);
                                }
                            }
                        });
                    }
                }

                if ($this->valueOr($eventValues)) {
                    $q->whereHas('event', function ($q) use ($event) {
                        if ($event['search']) {
                            $q->where(function ($q) use ($event) {
                                $q->orWhere('events.title', 'like', "%{$event['search']}%")
                                    ->orWhere('events.description', 'like', "%{$event['search']}%");
                            });
                        }

                        if ($event['category']) {
                            $q->whereHas('category', function ($q) use ($event) {
                                $q->where('event_categories.title', $event['category']);
                            });
                        }

                        if ($event['type'] == '實體') {
                            $q->where('events.type', 'physical');
                        } elseif ($event['type'] == '線上') {
                            $q->where('events.type', 'online');
                        }

                        if ($event['canRegisterGroup'] == '一般會員') {
                            $q->where('events.canRegisterGroup', 7);
                        } elseif ($event['canRegisterGroup'] == '經銷會員') {
                            $q->where('events.canRegisterGroup', 6);
                        }

                        $brands = $event['brands'] ?: [];
                        if (count($brands)) {
                            $q->whereHas('brands', function ($q) use ($brands) {
                                $q->whereIn('brands.title', $brands);
                            });
                        }

                        if ($event['dateType'] == '單天') {
                            $q->where('events.dateType', 'single');
                        } elseif ($event['dateType'] == '多天') {
                            $q->where('events.dateType', 'multiple');
                        } elseif ($event['dateType'] == '系列') {
                            $q->where('events.dateType', 'series');
                        }

                        if ($event['registrationType'] == '統一報名') {
                            $q->where('events.registrationType', 'impartial');
                        } elseif ($event['registrationType'] == '依場次報名') {
                            $q->where('events.registrationType', 'partial');
                        }

                        $startDate = $event['startDate'];
                        $endDate = $event['endDate'];
                        if ($startDate || $endDate) {
                            $q->whereHas('dates', function ($q) use ($startDate, $endDate) {
                                if ($startDate) {
                                    $q->where('event_dates.date', '>=', RequestHelper::toSystemTime($startDate));
                                }
                                if ($endDate) {
                                    $q->where('event_dates.date', '<=', RequestHelper::toSystemTime($endDate));
                                }
                            });
                        }
                    });
                }
            });
        }

        $validated->put('q', $q);
    }

    public function valueOr(array $array)
    {
        $v = 0;
        foreach ($array as $value) {
            $v = $v || $value;
        }

        return $v;
    }
}
