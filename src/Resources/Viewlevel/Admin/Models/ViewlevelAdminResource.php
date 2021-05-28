<?php

namespace DaydreamLab\User\Resources\Viewlevel\Admin\Models;

use DaydreamLab\JJAJ\Traits\FormatDateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewlevelAdminResource extends JsonResource
{
    use FormatDateTime;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user('api');
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'canDelete'     => $this->canDelete,
            'ordering'      => $this->ordering,
            'createdAt'     => $this->getDateTimeString($this->created_at, $user->timezone),
            'updatedAt'     => $this->getDateTimeString($this->updated_at, $user->timezone),
            'groups'        => $this->groups->map(function ($group) {
                    return $group->only(['id', 'title']);
                })->all(),
        ];
    }
}
