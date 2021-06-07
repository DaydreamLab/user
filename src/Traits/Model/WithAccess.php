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
}
