<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class LeadResource extends JsonResource
{
    public function toArray($request)
    {
        $leadData = json_decode($this->resource->data);

        $data = new Collection();

        foreach ($this->resource->tenant->customFields as $customField) {
            $data->put(
                $customField->slug,
                $customField->getValue($leadData->{$customField->slug} ?? null)
            );
        }

        return $data->toArray();
    }
}
