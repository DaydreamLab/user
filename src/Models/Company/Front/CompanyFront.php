<?php
namespace DaydreamLab\User\Models\Company\Front;

use DaydreamLab\User\Models\Company\Company;

class CompanyFront extends Company
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    protected $hidden = [
        'id',
        'category_id',
        'category',
        'locked_by',
        'locked_at',
        'ordering',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'state_'
    ];
}
