<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index(): View
    {
        $user = Auth::user();

        // إحصائيات سريعة
        $stats = $this->getDashboardStats();

        // آخر العمليات
        $recentActivities = $this->getRecentActivities();

        return view('dashboard.index', compact('stats', 'recentActivities'));
    }

    /**
     * الحصول على إحصائيات لوحة التحكم
     */
    private function getDashboardStats(): array
    {
        return [
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'total_invoices' => Invoice::count(),
            'total_payments' => Payment::sum('amount'),
            'total_expenses' => Expense::sum('amount'),
            'monthly_revenue' => $this->getMonthlyRevenue(),
        ];
    }

    /**
     * الحصول على الإيرادات الشهرية
     */
    private function getMonthlyRevenue(): float
    {
        $currentMonth = now()->startOfMonth();

        return Invoice::where('created_at', '>=', $currentMonth)
            ->sum('total_amount');
    }

    /**
     * الحصول على آخر الأنشطة
     */
    private function getRecentActivities(): array
    {
        return [
            'recent_invoices' => Invoice::latest()->take(5)->get(),
            'recent_payments' => Payment::latest()->take(5)->get(),
            'recent_customers' => Customer::latest()->take(5)->get(),
        ];
    }
}
