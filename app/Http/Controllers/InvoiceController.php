<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * عرض قائمة الفواتير
     */
    public function index(): View
    {
        $invoices = Invoice::with(['customer', 'branch', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * عرض نموذج إنشاء فاتورة جديدة
     */
    public function create(): View
    {
        $customers = Customer::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        return view('invoices.create', compact('customers', 'products'));
    }

    /**
     * حفظ فاتورة جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'contract_number' => 'required|string|max:100',
            'contract_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $invoice = Invoice::create([
                'customer_id' => $request->input('customer_id'),
                'sale_date' => $request->input('sale_date'),
                'contract_number' => $request->input('contract_number'),
                'notes' => $request->input('notes'),
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // إضافة عناصر الفاتورة
            foreach ($request->input('items') as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            // رفع صورة العقد
            if ($request->hasFile('contract_image')) {
                $contractImagePath = $request->file('contract_image')->store('contracts', 'public');
                $invoice->update(['contract_image' => $contractImagePath]);
            }

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفاتورة: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل الفاتورة
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'branch', 'items.product', 'payments']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * عرض نموذج تعديل الفاتورة
     */
    public function edit(Invoice $invoice): View
    {
        $customers = Customer::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * تحديث فاتورة
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'contract_number' => 'required|string|max:100',
            'contract_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        // رفع صورة العقد الجديدة
        if ($request->hasFile('contract_image')) {
            $contractImageFile = $request->file('contract_image');

            // حذف الصورة القديمة
            if ($invoice->contract_image) {
                Storage::disk('public')->delete($invoice->contract_image);
            }

            if ($contractImageFile instanceof \Illuminate\Http\UploadedFile) {
                $contractImagePath = $contractImageFile->store('contracts', 'public');
                if ($contractImagePath !== false) {
                    $invoice->contract_image = $contractImagePath;
                }
            }
        }

        $invoice->update([
            'customer_id' => $request->input('customer_id'),
            'sale_date' => $request->input('sale_date'),
            'contract_number' => $request->input('contract_number'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح!');
    }

    /**
     * حذف الفاتورة
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        try {
            $invoice->delete();

            return redirect()->route('invoices.index')
                ->with('success', 'تم حذف الفاتورة بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الفاتورة: '.$e->getMessage()]);
        }
    }

    /**
     * البحث في الفواتير
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $invoices = Invoice::where('contract_number', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with(['customer', 'branch', 'items'])
            ->paginate(20);

        return view('invoices.index', compact('invoices', 'query'));
    }

    /**
     * طباعة الفاتورة
     */
    public function print(Invoice $invoice): View
    {
        $invoice->load(['customer', 'branch', 'items.product', 'payments']);

        return view('invoices.print', compact('invoice'));
    }
}
