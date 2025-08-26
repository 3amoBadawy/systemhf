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
        /** @var \App\Models\Customer $customer */
        $customer = $this->resource;

        $includeDetails = $request->boolean('include_details', false);
        $includeTimestamps = $request->boolean('include_timestamps', true);

        $data = [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'phone2' => $customer->phone2,
            'country' => $customer->country,
            'governorate' => $customer->governorate,
            'address' => $customer->address,
            'notes' => $customer->notes,
            'is_active' => $customer->is_active,
            'branch' => $this->whenLoaded('branch', function () use ($customer) {
                return [
                    'id' => $customer->branch?->id,
                    'name' => $customer->branch?->name,
                ];
            }),
            'total_invoiced' => $customer->total_invoiced,
            'total_paid' => $customer->total_paid,
            'remaining_balance' => $customer->remaining_balance,
            'payment_status' => $customer->payment_status,
            'payment_status_text' => $customer->payment_status_text,
            'has_outstanding_balance' => $customer->hasOutstandingBalance(),
            'unread_notifications_count' => $customer->unread_notifications_count,
        ];

        if ($includeDetails) {
            $data['additional_info'] = [
                'registration_date' => $customer->created_at?->format('Y-m-d'),
                'last_updated' => $customer->updated_at?->format('Y-m-d'),
            ];
        }

        if ($includeTimestamps) {
            $data['created_at'] = $customer->created_at?->toISOString();
            $data['updated_at'] = $customer->updated_at?->toISOString();
        }

        return $data;
    }
}
