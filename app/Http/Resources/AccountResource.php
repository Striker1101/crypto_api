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
            'trade' => $this->trade,
            'account_type' => $this->account_type,
            'trade_changed_at ' => $this->trade_changed_at,
            'account_stage' => $this->account_stage,
            'updated_at' => $this->updated_at,
        ];
    }
}
