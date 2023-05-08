<?php

namespace DaydreamLab\User\Repositories\CompanyOrder\Admin;

use Carbon\Carbon;
use DaydreamLab\User\Models\CompanyOrder\Admin\CompanyOrderAdmin;
use DaydreamLab\User\Repositories\CompanyOrder\CompanyOrderRepository;
use Illuminate\Support\Collection;

class CompanyOrderAdminRepository extends CompanyOrderRepository
{
    public function __construct(CompanyOrderAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function search(Collection $data)
    {
        $q = $data->get('q');

        if ($startDate = $data->pull('startDate')) {
            $q->where(
                'date',
                '>=',
                Carbon::parse($startDate . '-01', 'Asia/Taipei')->startOfDay()->tz('UTC')->toDateTimeString()
            );
        }
        if ($endDate = $data->pull('endDate')) {
            $q->where(
                'date',
                '<=',
                Carbon::parse($endDate . '-01', 'Asia/Taipei')->endOfMonth()->tz('UTC')->toDateTimeString()
            );
        }
        return parent::search($data);
    }
}
