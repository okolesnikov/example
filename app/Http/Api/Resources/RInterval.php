<?php

namespace App\Http\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RInterval extends JsonResource
{
    public function __construct(public array $data)
    {
        parent::__construct($data);
    }

    public function toArray($request): array
    {
        return [
            'free_intervals' => $this->data
        ];
    }
}
