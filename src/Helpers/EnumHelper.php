<?php

namespace DaydreamLab\User\Helpers;

class EnumHelper
{
    public const SITE_USER_GROUPS = [
        '一般會員',
        '經銷會員',
        '外部會員',
        '無手機名單'
    ];

    public const CAN_MARKETING = [
        '可行銷',
        '不可行銷'
    ];

    public const COMPANY_NOTES = [
        self::COMPANY_NOTE_NONE,
        self::COMPANY_NOTE_COMPETITION,
        self::COMPANY_NOTE_BLACKLIST,
        self::COMPANY_NOTE_STAFF,
        self::COMPANY_NOTE_OBM,
    ];

    public const DEFFAULT_CRM_RULES = [
        'basic' => [
            'userGroup' => '',
            'lineBind' => '',
            'createdAtFrom' => '',
            'createdAtTo' => '',
            'lastLoginAtFrom' => '',
            'lastLoginAtTo' => '',
            'block' => '',
            'lastUpdate' => '',
            'lastUpdateFrom' => '',
            'lastUpdateTo' => '',
            'purchaseRoles' => [],
            'jobTypes' => [],
            'jobCategories' => [],
            'interestedIssues' => [],
            'subscription' => '',
            'canMarketing' => ''
        ],
        'company' => [
            'search' => [],
            'city' => [],
            'categoryNotes' => [],
            'industry' => '',
            'scale' => '',
            'approvedFrom' => '',
            'approvedTo' => '',
            'expiredFrom' => '',
            'expiredTo' => '',
        ],
        'companyOrder' => [
            'enable' => '否',
            'brands' => [],
            'startDate' => '',
            'endDate' => '',
        ],
        'event' => [
            'search' => '',
            'category' => '',
            'type' => '',
            'canRegisterGroup' => '',
            'dateType' => '',
            'registrationType' => '',
            'brands' => [],
            'startDate' => '',
            'endDate' => '',
            'isOuter' => '',
            'waiting' => '',
        ],
        'order' => [
            'replyQuestionnaire' => '',
            'regStatus' => '',
            'participateTimesFrom' => '',
            'participateTimesTo' => '',
            'cancelTimesFrom' => '',
            'cancelTimesTo' => '',
            'noshowTimesFrom' => '',
            'noshowTimesTo' => '',
        ],
        'coupon' => [
            'type' => '',
            'userGroup' => '',
            'useTimesFrom' => '',
            'useTimesTo' => '',
        ],
        'menu' => [
            'id' => '',
            'action' => '',
            'value' => '',
            'startDate' => '',
            'endDate' => '',
        ],
        'except' => [
            'lastLoginDate' => '',
            'userGroup' => '',
            'companySearch' => [],
            'companyCategoryNotes' => [],
            'cancelTimesFrom' => '',
            'cancelTimesTo' => '',
            'noshowTimesFrom' => '',
            'noshowTimesTo' => '',
        ]
    ];

    public const COMPANY_APPROVED = 'APPROVED';
    public const COMPANY_NEW = 'NEW';
    public const COMPANY_NONE = 'NONE';
    public const COMPANY_PENDING = 'PENDING';
    public const COMPANY_REJECTED = 'REJECTED';

    public const COMPANY_NOTE_NONE = '無';
    public const COMPANY_NOTE_COMPETITION = '競業';
    public const COMPANY_NOTE_BLACKLIST = '黑名單';
    public const COMPANY_NOTE_STAFF = '員工';
    public const COMPANY_NOTE_OBM = '原廠';

    public const COMPANY_CATEGORY_NORMAL = '一般';
    public const COMPANY_CATEGORY_DEALER = '經銷會員';

    public const COMPANY_ORDER_BRAND_INTERSECT = '符合全部勾選';
    public const COMPANY_ORDER_BRAND_UNION = '符合其一勾選';

    public const WAIT_UPDATE = '待更新';
    public const ALREADY_UPDATE = '已更新';

    public const DEALER_VALIDATE_PASS = '已驗證';
    public const DEALER_VALIDATE_WAIT = '未驗證';
    public const DEALER_VALIDATE_EXPIRED = '已逾期';

    public const SUBSCRIBE_SELF_CANCEL = '自行取消';
    public const SUBSCRIBE_EMAIL_CANCEL = 'Email通知';
    public const SUBSCRIBE_PHONE_CANCEL = '電話通知';
    public const SUBSCRIBE_SALES_CANCEL = '業務通知';

    public const USERTAG_BASIC_CHECK_KEYS = [
        'createdAtFrom'     => 'startOfDay',
        'createdAtTo'       => 'endOfDay',
        'lastLoginAtFrom'   => 'startOfDay',
        'lastLoginAtTo'     => 'endOfDay',
        'lastUpdateFrom'    => 'startOfDay',
        'lastUpdateTo'      => 'endOfDay',
    ];

    public const USERTAG_EVENT_CHECK_KEYS = [
        'startDate' => null,
        'endDate'   => null
    ];

    public const USERTAG_MENU_CHECK_KEYS = [
        'startDate' => 'startOfDay',
        'endDate'   => 'endOfDay'
    ];

    public const USERTAG_COMPANY_CHECK_KEYS = [
        'approvedFrom' => 'startOfDay',
        'approvedTo'   => 'endOfDay',
        'expiredFrom' => 'startOfDay',
        'expiredTo'   => 'endOfDay'
    ];

    public static function constant($name)
    {
        return constant('self::' . $name);
    }
}