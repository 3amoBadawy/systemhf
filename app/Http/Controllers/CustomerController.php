<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct(
        private \App\Services\CustomerService $customerService
    ) {}

    /**
     * تحديث عميل
     */
    public function update(CustomerRequest $request, Customer $customer): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->customerService->updateCustomer($customer->id, $request->validated());

            return redirect()->route('customers.index')
                ->with('success', 'تم تحديث العميل بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث العميل: '.$e->getMessage()]);
        }
    }
}
