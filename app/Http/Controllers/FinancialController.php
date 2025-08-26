<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FinancialController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للمالية
     */
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();

        // إحصائيات مالية
        $financialStats = $this->getFinancialStats();

        // آخر المعاملات المالية
        $recentTransactions = $this->getRecentTransactions();

        return view('financial.index', compact('financialStats', 'recentTransactions'));
    }

    /**
     * الحصول على الإحصائيات المالية
     */
    private function getFinancialStats(): array
    {
        return [
            'total_revenue' => Invoice::sum('total'),
            'total_payments' => Payment::sum('amount'),
            'total_expenses' => Expense::sum('amount'),
            'net_profit' => $this->calculateNetProfit(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'outstanding_invoices' => $this->getOutstandingInvoices(),
        ];
    }

    /**
     * حساب صافي الربح
     */
    private function calculateNetProfit(): float
    {
        $totalRevenue = Invoice::sum('total');
        $totalExpenses = Expense::sum('amount');

        return $totalRevenue - $totalExpenses;
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
     * الحصول على الفواتير المعلقة
     */
    private function getOutstandingInvoices(): float
    {
        return Invoice::where('payment_status', 'pending')
            ->sum('total');
    }

    /**
     * الحصول على آخر المعاملات
     */
    private function getRecentTransactions(): array
    {
        return [
            'recent_invoices' => Invoice::latest()->take(10)->get(),
            'recent_payments' => Payment::latest()->take(10)->get(),
            'recent_expenses' => Expense::latest()->take(10)->get(),
        ];
    }
}
