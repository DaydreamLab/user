<?php

namespace DaydreamLab\User\Helpers;

class EnumHelper
{
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

    public const WAIT_UPDATE = '待更新狀態';
    public const ALREADY_UPDATE = '已更新狀態';

    public const SUBSCRIBE_SELF_CANCEL = '自行取消';
    public const SUBSCRIBE_EMAIL_CANCEL = 'Email通知';
    public const SUBSCRIBE_PHONE_CANCEL = '電話通知';
    public const SUBSCRIBE_SALES_CANCEL = '業務通知';
}