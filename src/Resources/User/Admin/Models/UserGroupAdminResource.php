<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

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
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'parent_id'     => $this->parent_id,
            'tree_title'    => $this->tree_title,
            'description'   => $this->tree_title,
            'canDelete'     => $this->canDelete,
            'apis'          => $this->apis->map(function ($api) {
                return $api->id;
            }),
            'assets'        => $this->assets->map(function ($asset) {
                return $asset->id;
            }),
            'ordering'      => $this->ordering,
            'redirect'      => $this->redirect,
        ];
    }
}
