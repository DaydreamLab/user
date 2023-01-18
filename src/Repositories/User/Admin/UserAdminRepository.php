<?php

namespace DaydreamLab\User\Repositories\User\Admin;

use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\User\Models\User\Admin\UserAdmin;
use Illuminate\Support\Collection;

class UserAdminRepository extends UserRepository
{
    public function __construct(UserAdmin $model)
    {
        parent::__construct($model);
    }


    public function search(Collection $data)
    {
        $q = $data->get('q');

        $search = $data->pull('search');
        if ($search) {
            $q->where(function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobilePhone', 'like', "%{$search}%");
                })->orWhere(function ($q) use ($search) {
                    $q->whereIn('id', function ($q) use ($search) {
                        $q->select('user_id')
                            ->from('users_companies')
                            ->orWhere('users_companies.email', 'like', "%{$search}%")
                            ->orWhereIn('company_id', function ($q) use ($search) {
                                $q->select('id')
                                    ->from('companies')
                                    ->where(function ($q) use ($search) {
                                        $q->orWhere('companies.name', 'like', "%{$search}%")
                                            ->orWhere('companies.vat', 'like', "%$search%");
                                    });
                            });
                    });
                });
            });
        }

        $q->whereHas('company', function ($q) use ($data) {
            if ($company_id = $data->get('company_id')) {
                $q->where('users_companies.id', $company_id);
            }
            if ($updateStatus = $data->pull('updateStatus')) {
                $q->where(function ($q) use ($updateStatus) {
                    if ($updateStatus == EnumHelper::ALREADY_UPDATE) {
                        $q->whereNotNull('lastUpdate')
                            ->where('lastUpdate', '>', now()->subDays(config('daydreamlab.user.userCompanyUpdateInterval', 120))->toDateTimeString());
                    } else {
                        $q->whereNull('lastUpdate')
                            ->orWhere(function ($q) {
                                $q->whereNotNull('lastUpdate')
                                    ->where('lastUpdate', '<', now()->subDays(config('daydreamlab.user.userCompanyUpdateInterval', 120))->toDateTimeString());
                            });
                    }
                });
            }
        });

        $q->with([
            'company',
            'company.company',
            'newsletterSubscription',
            'newsletterSubscription.newsletterCategories'
        ]);
        $data->put('q', $q);

        return parent::search($data);
    }
}