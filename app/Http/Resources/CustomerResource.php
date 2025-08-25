<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'phone2' => $this->phone2,
            'country' => $this->country,
            'governorate' => $this->governorate,
            'address' => $this->address,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id' => $this->branch->id,
                    'name' => $this->branch->name,
                ];
            }),
            'total_invoiced' => $this->total_invoiced,
            'total_paid' => $this->total_paid,
            'remaining_balance' => $this->remaining_balance,
            'payment_status' => $this->payment_status,
            'payment_status_text' => $this->payment_status_text,
            'has_outstanding_balance' => $this->hasOutstandingBalance(),
            'unread_notifications_count' => $this->unread_notifications_count,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
