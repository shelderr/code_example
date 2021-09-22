<?php

namespace App\Http\Resources\Roles;

use App\Enums\BaseAppEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $pagination = $request->paginate ?? BaseAppEnum::DEFAULT_PAGINATION;

        return [
            'id'   => $this->id,
            'name' => $this->name,
            'childrens' => $this->childrensOf($this->id)->paginate($pagination),
        ];
    }
}
