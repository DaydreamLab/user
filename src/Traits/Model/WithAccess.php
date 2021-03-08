<?php

namespace DaydreamLab\User\Traits\Model;

use DaydreamLab\User\Models\Viewlevel\Viewlevel;

trait WithAccess
{
    public function getAccessTitleAttribute()
    {
        $viewlevel = $this->viewlevel;

        return  $viewlevel ? $viewlevel->title : null;
    }


    public function viewlevel()
    {
        return $this->hasOne(Viewlevel::class, 'id', 'access')
            ->with('groups');
    }


//    public function getAccessIds()
//    {
//        if(!$this->access_ids) {
//            if($this->getUser()) {
//                $this->access_ids = $this->getUser()->access_ids;
//            } else {
//                $this->access_ids = config('daydreamlab.cms.item.front.access_ids');
//            }
//        }
//
//        return $this->access_ids;
//    }

}