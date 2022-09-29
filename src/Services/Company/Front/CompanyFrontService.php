<?php

namespace DaydreamLab\User\Services\Company\Front;

use DaydreamLab\JJAJ\Exceptions\BadRequestException;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Repositories\Company\Front\CompanyFrontRepository;
use DaydreamLab\User\Services\Company\CompanyService;
use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use Illuminate\Support\Collection;

class CompanyFrontService extends CompanyService
{
    protected $modelType = 'Front';

    public function __construct(CompanyFrontRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function apply(Collection $input)
    {
        $user = $input->get('user');
        $input->put('applyUserId', $user->id);
        $userCompany = $user->company;
        if (!$userCompany->vat) {
            throw new ForbiddenException('CompanyVatEmpty', null, null, 'User');
        }

        $company = $this->findBy('vat', '=', $userCompany->vat)->first();
        if (!$company) {
            $input->put('category_id', 3); # 一般
            $result = $this->add($input);
        } else {
            if ($company->status != EnumHelper::COMPANY_NEW) {
                throw new ForbiddenException('StatusInvalid', ['status' => $company->status]);
            }
            $input->put('id', $company->id);
            $input->put('status', EnumHelper::COMPANY_PENDING);
            $result = $this->update($company, $input);
        }

        $this->status = $result ? 'ApplySuccess' : 'ApplyFail';

        return $this->response;
    }


    public function getInfo($vat)
    {
        $company = $this->findBy('vat', '=', $vat)->first();

        $this->status = $company
            ? 'GetItemSuccess'
            : 'ItemNotExist';
        $this->response = $company;
        return $this->response;
    }
}
