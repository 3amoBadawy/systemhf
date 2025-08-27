<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private \App\Services\CustomerService $customerService
    ) {}

    /**
     * عرض قائمة العملاء
     */
    public function index(): View
    {
        $customers = Customer::with(['branch', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('customers.index', compact('customers'));
    }

    /**
     * عرض نموذج إنشاء عميل جديد
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * حفظ عميل جديد
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        try {
            $this->customerService->createCustomer($request->validated());

            return redirect()->route('customers.index')
                ->with('success', 'تم إنشاء العميل بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء العميل: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل العميل
     */
    public function show(Customer $customer): View
    {
        $customer->load(['branch', 'category', 'invoices', 'payments']);

        return view('customers.show', compact('customer'));
    }

    /**
     * عرض نموذج تعديل العميل
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * تحديث عميل
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
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

    /**
     * حذف العميل
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            $customer->delete();

            return redirect()->route('customers.index')
                ->with('success', 'تم حذف العميل بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف العميل: '.$e->getMessage()]);
        }
    }

    /**
     * البحث في العملاء
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->with(['branch', 'category'])
            ->paginate(20);

        return view('customers.index', compact('customers', 'query'));
    }

    /**
     * البحث السريع في العملاء
     */
    public function quickSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->get('q');
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name', 'phone', 'email']);

        return response()->json($customers);
    }

    /**
     * البحث المتقدم في العملاء
     */
    public function ajaxSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->get('q');
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->with(['branch:id,name_ar', 'category:id,name_ar'])
            ->take(20)
            ->get();

        return response()->json($customers);
    }

    /**
     * عرض فواتير العميل
     */
    public function getInvoices(Customer $customer): View
    {
        $invoices = $customer->invoices()
            ->with(['items', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('customers.invoices', compact('customer', 'invoices'));
    }
}
