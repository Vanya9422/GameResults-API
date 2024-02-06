<?php

namespace App\Http\Resources\Result;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $milliseconds
 * @property int $id
 * @property ?int $member_id
 * @property string $created_at
 */
class ResultResource extends JsonResource
{
    /**
     * Преобразует ресурс в массив.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'milliseconds' => $this->milliseconds,
            'created_at' => $this->created_at,
        ];
    }
}
