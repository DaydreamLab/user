<?php

namespace DaydreamLab\User\Services\Viewlevel\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;
use Illuminate\Support\Str;

class ViewlevelAdminService extends ViewlevelService
{
    protected $type = 'ViewlevelAdmin';

    public function __construct(ViewlevelAdminRepository $repo)
    {
        parent::__construct($repo);
    }


    public function getList()
    {
        $items = $this->repo->all();

        $data = [];
        foreach ($items as $item)
        {
            $temp = [];
            $temp['id'] = $item->id;
            $temp['title'] = $item->title;
            $data[] = $temp;
        }

        $this->status   =  Str::upper(Str::snake($this->type .'GetListSuccess'));
        $this->response = $data;

        return $data;
    }
}
