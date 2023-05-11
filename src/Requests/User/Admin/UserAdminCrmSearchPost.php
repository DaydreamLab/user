<?php

namespace DaydreamLab\User\Requests\User\Admin;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Exceptions\BadRequestException;
use DaydreamLab\JJAJ\Requests\ListRequest;
use DaydreamLab\User\Helpers\CompanyRequestHelper;
use DaydreamLab\User\Helpers\EnumHelper;
use Illuminate\Validation\Rule;
use DaydreamLab\Cms\Helpers\EnumHelper as CmsEnumHelper;

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
            'search' => 'nullable|string',
            'basic' => 'required|array',
            /**** 基本資料****/
            'basic.userGroup' => ['nullable', Rule::in(['一般會員', '經銷會員'])],
            # LINE綁定
            'basic.lineBind' => ['nullable', Rule::in(['是', '否'])],
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
            'basic.purchaseRoles' => 'nullable|array',
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
            'basic.subscription' => [
                'nullable',
                Rule::in([
                    CmsEnumHelper::NEWSLETTER_NONESUBSCRIBE,
                    CmsEnumHelper::NEWSLETTER_SUBSCRIBE,
                    CmsEnumHelper::NEWSLETTER_UNSUBSCRIBE
                ])
            ],

            /***** 公司 *****/
            'company' => 'required|array',
            'company.search' => 'nullable|array',
            'company.search.*' => 'required|string',
            # 公司所在城市
            'company.city' => 'nullable|array',
            'company.city.*' => 'required|string',
            # 公司分類註記
            'company.categoryNotes' => 'nullable|array',
            'company.categoryNotes.*' => [
                'required',
                Rule::in([
                    EnumHelper::COMPANY_NOTE_STAFF,
                    EnumHelper::COMPANY_NOTE_COMPETITION,
                    EnumHelper::COMPANY_NOTE_BLACKLIST,
                    EnumHelper::COMPANY_NOTE_NONE,
                    EnumHelper::COMPANY_NOTE_OBM
                ])
            ],
            # 公司產業別
            'company.industry' => 'nullable|array',
            'company.industry.*' => ['required', 'string'],
            # 公司規模
            'company.scale' => 'nullable|string',

            /** 公司銷售記錄 */
            'companyOrder' => 'required|array',
            'companyOrder.enable' => ['required', Rule::in(['是', '否'])],
            'companyOrder.brands' => 'nullable|array',
            'companyOrder.brands.*' => 'required|integer',
            'companyOrder.type' => [
                'nullable',
                Rule::in([
                    EnumHelper::COMPANY_ORDER_BRAND_INTERSECT,
                    EnumHelper::COMPANY_ORDER_BRAND_UNION
                ])
            ],
            'companyOrder.startDate' => 'nullable|date_format:Y-m',
            'companyOrder.endDate' => 'nullable|date_format:Y-m',

            /**** 活動課程 ****/
            'event' => 'required|array',
            'event.search' => 'nullable|string',
            'event.category' => ['nullable', Rule::in(['不拘', '課程', '活動'])],
            'event.type' => ['nullable', Rule::in(['不拘', '實體', '線上'])],
            'event.canRegisterGroup' => ['nullable', Rule::in(['不拘', '一般會員', '經銷會員'])],
            'event.dateType' => ['nullable', Rule::in(['不拘', '單天', '多天', '系列'])],
            'event.registrationType' => ['nullable', Rule::in(['不拘', '統一報名', '依場次報名'])],
            'event.brands' => 'nullable|array',
            'event.brands.*' => 'required|string',
            'event.startDate' => 'nullable|required_with:event.endDate|date_format:Y-m-d',
            'event.endDate' => 'nullable|required_with:event.startDate|date_format:Y-m-d',

            # todo: 是否備取
            'order' => 'required|array',
            'order.replyQuestionnaire' => ['nullable', Rule::in(['不拘', '是', '否'])],
            'order.regStatus' => ['nullable', Rule::in(['報名成功', '報名取消'])],
            'order.participateTimesFrom' => 'nullable|integer',
            'order.participateTimesTo' => 'nullable|integer',
            'order.cancelTimesFrom' => 'nullable|integer',
            'order.cancelTimesTo' => 'nullable|integer',
            'order.noshowTimesFrom' => 'nullable|integer',
            'order.noshowTimesTo' => 'nullable|integer',
            #上課券
            'coupon' => 'required|array',
            'coupon.type' => ['nullable', Rule::in(['不拘', '一般上課券', '批次上課券'])],
            'coupon.userGroup' => ['nullable', Rule::in(['不拘', '一般會員', '經銷會員'])],
            'coupon.useTimesFrom' => 'nullable|integer',
            'coupon.useTimesTo' => 'nullable|integer',
            # 選端訪問（網路行為）
            'menu' => 'required|array',
            'menu.id' => 'nullable|integer',
            'menu.action' => ['nullable', Rule::in(['', '點擊', '停留時間'])],
            'menu.value' => 'nullable|integer',
            'menu.startDate' => 'nullable|date_format:Y-m-d',
            'menu.endDate' => 'nullable|date_format:Y-m-d',
            # 排除項目
            'except' => 'required|array',
            'except.lastLoginDate' => 'nullable|integer',
            'except.userGroup' => ['nullable', Rule::in(['一般會員', '經銷會員'])],
            'except.companySearch' => 'nullable|array',
            'except.companySearch.*' => 'required|string',
            'except.companyCategoryNotes' => 'nullable|array',
            'except.companyCategoryNotes.*' => [
                'nullable',
                Rule::in([
                    EnumHelper::COMPANY_NOTE_STAFF,
                    EnumHelper::COMPANY_NOTE_COMPETITION,
                    EnumHelper::COMPANY_NOTE_BLACKLIST,
                    EnumHelper::COMPANY_NOTE_NONE,
                    EnumHelper::COMPANY_NOTE_OBM
                ])
            ],
            'except.cancelTimesFrom' => 'nullable|integer',
            'except.cancelTimesTo' => 'nullable|integer',
            'except.noshowTimesFrom' => 'nullable|integer',
            'except.noshowTimesTo' => 'nullable|integer',
            'export' => ['nullable', Rule::in([0, 1])],
            'userTags' => 'nullable|array',
            'userTags.*' => 'required|array',
            'userTags.*.id' => 'required|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }


    /**
     * @throws BadRequestException
     */
    public function validated()
    {
        $validated = parent::validated();
        $validated->put('companyOrder', CompanyRequestHelper::handleCompanyOrder($validated->get('companyOrder')));

        return $validated;
    }
}