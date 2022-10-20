<?php

namespace DaydreamLab\User\Services\CompanyOrder\Admin;

use DaydreamLab\User\Repositories\CompanyOrder\Admin\CompanyOrderAdminRepository;
use DaydreamLab\User\Services\CompanyOrder\CompanyOrderService;
use DaydreamLab\User\Services\CompanyOrderItem\Admin\CompanyOrderItemAdminService;
use Illuminate\Support\Collection;

class CompanyOrderAdminService extends CompanyOrderService
{
    protected $companyOrderItemAdminService;

    public function __construct(
        CompanyOrderAdminRepository $repo,
        CompanyOrderItemAdminService $companyOrderItemAdminService
    ) {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->companyOrderItemAdminService = $companyOrderItemAdminService;
    }


    public function afterAdd(Collection $input, $item)
    {
        $itemsData = $input->get('items') ?: [];
        foreach ($itemsData as $itemData) {
            unset($itemsData['id']);
            $itemData['orderId'] = $item->id;
            $this->companyOrderItemAdminService->store(collect($itemData));
        }
    }


    public function afterModify(Collection $input, $item)
    {
        $itemsData = $input->get('items') ?: [];
        foreach ($itemsData as $itemData) {
            $itemData['orderId'] = $item->id;
            $this->companyOrderItemAdminService->store(collect($itemData));
        }

        $deleteItemIds = $input->get('deleteItems') ?: [];
        if (count($deleteItemIds)) {
            $this->companyOrderItemAdminService->remove(collect(['ids' => $deleteItemIds]));
        }
    }
}
