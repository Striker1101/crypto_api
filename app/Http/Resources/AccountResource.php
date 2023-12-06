<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'balance' => $this->balance,
            'earning' => $this->earning,
            'bonus' => $this->bonus,
            'account_type' => $this->account_type,
            'account_stage' => $this->account_stage,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
