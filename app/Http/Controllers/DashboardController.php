<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();

        // إحصائيات سريعة
        $stats = $this->getDashboardStats();

        // آخر العمليات
        $recentActivities = $this->getRecentActivities();

        // متغيرات إضافية مطلوبة للعرض
        $dashboardData = $this->getDashboardData();

        return view('dashboard.index', array_merge($stats, $recentActivities, $dashboardData));
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
            ->sum('total');
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

    /**
     * الحصول على بيانات إضافية للعرض
     */
    private function getDashboardData(): array
    {
        $currentMonth = now()->startOfMonth();

        return [
            // إحصائيات العملاء
            'customersCount' => Customer::count(),
            'newCustomersThisMonth' => Customer::where('created_at', '>=', $currentMonth)->count(),

            // إحصائيات الفواتير
            'invoicesCount' => Invoice::count(),
            'totalInvoiced' => Invoice::sum('total'),
            'pendingInvoices' => Invoice::where('payment_status', 'pending')->count(),
            'completedInvoices' => Invoice::where('payment_status', 'completed')->count(),

            // إحصائيات المدفوعات
            'paymentsCount' => Payment::count(),
            'totalPaid' => Payment::sum('amount'),
            'paymentsThisMonth' => Payment::where('created_at', '>=', $currentMonth)->sum('amount'),

            // إحصائيات المنتجات
            'productsCount' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),

            // إحصائيات طرق الدفع
            'paymentMethodsCount' => PaymentMethod::count(),
            'activePaymentMethods' => PaymentMethod::where('is_active', true)->count(),

            // الإحصائيات الشهرية
            'monthlyStats' => $this->getMonthlyStats(),

            // طرق الدفع الأكثر استخداماً
            'topPaymentMethods' => $this->getTopPaymentMethods(),

            // أحدث البيانات
            'recentInvoices' => Invoice::with('customer')->latest()->take(5)->get(),
            'recentPayments' => Payment::with('customer')->latest()->take(5)->get(),
        ];
    }

    /**
     * الحصول على الإحصائيات الشهرية
     */
    private function getMonthlyStats(): array
    {
        $months = [];
        $invoices = [];
        $payments = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $startOfMonth = $date->startOfMonth();
            $endOfMonth = $date->endOfMonth();

            $invoices[] = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $payments[] = Payment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount');
        }

        return [
            'months' => $months,
            'invoices' => $invoices,
            'payments' => $payments,
        ];
    }

    /**
     * الحصول على طرق الدفع الأكثر استخداماً
     */
    private function getTopPaymentMethods(): array
    {
        return Payment::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->toArray();
    }
}
