<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\User\Resources\User\Admin\Collections\UserGroupAdminPageResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->handlePage($this->assetGroups, $this->apis);

        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'parent_id'     => $this->parent_id,
            'tree_title'    => $this->tree_title,
            'description'   => $this->tree_title,
            'canDelete'     => $this->canDelete,
            'apis'          => $this->apis,
            'assets'        => $this->assets,
            'ordering'      => $this->ordering,
            'redirect'      => $this->redirect,
        ];
    }


    public function handlePage($assetGroups, $apis)
    {

    }
}
