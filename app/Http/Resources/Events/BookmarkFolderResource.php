<?php

namespace App\Http\Resources\Events;

use App\Enums\BaseAppEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkFolderResource extends JsonResource
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
        $paginate = $request->paginate ?? BaseAppEnum::DEFAULT_PAGINATION;

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'events'      => $this->events()->orderBy('created_at', 'desc')->paginate($paginate),
            'persons'     => $this->persons()->orderBy('created_at', 'desc')->paginate($paginate),
            'collectives' => $this->collectives()->orderBy('created_at', 'desc')->paginate($paginate),
            'venues'      => $this->venues()->orderBy('created_at', 'desc')->paginate($paginate),
        ];
    }
}
