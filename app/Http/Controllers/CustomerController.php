<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * تحديث عميل
     */
    public function update(CustomerRequest $request, Customer $customer): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->customerService->updateCustomer($request, $customer);

            return redirect()->route('customers.index')
                ->with('success', 'تم تحديث العميل بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث العميل: '.$e->getMessage()]);
        }
    }
}
