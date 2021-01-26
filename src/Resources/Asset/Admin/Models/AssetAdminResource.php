<?php

namespace DaydreamLab\User\Resources\Asset\Admin\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'parent_id'         => $this->parent_id,
            'title'             => $this->title,
            'path'              => $this->path,
            'fullPath'          => $this->fullPath,
            'component'         => $this->component,
            'state'             => $this->state,
            'refirect'          => $this->redirect,
            'icon'              => $this->icon,
            'showNav'           => $this->showNav,
            'tree_title'        => $this->tree_title,
            'tree_list_title'   =>  $this->tree_list_title,
        ];
    }
}
