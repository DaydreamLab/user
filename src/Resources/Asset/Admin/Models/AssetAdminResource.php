<?php

namespace DaydreamLab\User\Resources\Asset\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class AssetAdminResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $timezone = $this->user($request)->timezone;

        return [
            'id'                => $this->id,
            'parent_id'         => $this->parent_id,
            'title'             => $this->title,
            'type'              => $this->type,
            'path'              => $this->path,
            'fullPath'          => $this->fullPath,
            'component'         => $this->component,
            'state'             => $this->state,
            'refirect'          => $this->redirect,
            'icon'              => $this->icon,
            'showNav'           => $this->showNav,
            'description'       => $this->description,
            'params'            => $this->params,
            'ordering'          => $this->ordering,
            'tree_title'        => $this->tree_title,
            'tree_list_title'   => $this->tree_list_title,
        ];
    }
}
