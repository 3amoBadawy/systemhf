<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FinancialController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للمالية
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // الحصول على الفرع المحدد أو الفرع الافتراضي
        $selectedBranchId = $request->get('branch_id', 1); // افتراضي الفرع الأول

        // الحصول على جميع الفروع
        $branches = Branch::where('status', 'active')->get();

        // الحصول على الحسابات للفرع المحدد
        $accounts = Account::where('branch_id', $selectedBranchId)
            ->where('status', 'active')
            ->get();

        // الحصول على طرق الدفع
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        // إحصائيات مالية
        $stats = $this->getFinancialStats($selectedBranchId);

        // آخر المعاملات المالية
        $recentTransactions = $this->getRecentTransactions();

        return view('financial.index', compact(
            'branches',
            'selectedBranchId',
            'accounts',
            'paymentMethods',
            'stats',
            'recentTransactions'
        ));
    }

    /**
     * الحصول على الإحصائيات المالية
     */
    private function getFinancialStats(int $branchId): array
    {
        return [
            'total_accounts' => Account::where('branch_id', $branchId)->where('status', 'active')->count(),
            'total_payment_methods' => PaymentMethod::where('status', 'active')->count(),
            'total_balance' => Account::where('branch_id', $branchId)->where('status', 'active')->sum('balance'),
            'branches_count' => Branch::where('status', 'active')->count(),
            'total_revenue' => Invoice::where('branch_id', $branchId)->sum('total'),
            'total_payments' => Payment::where('branch_id', $branchId)->sum('amount'),
            'total_expenses' => Expense::where('branch_id', $branchId)->sum('amount'),
            'net_profit' => $this->calculateNetProfit($branchId),
            'monthly_revenue' => $this->getMonthlyRevenue($branchId),
            'outstanding_invoices' => $this->getOutstandingInvoices($branchId),
        ];
    }

    /**
     * حساب صافي الربح
     */
    private function calculateNetProfit(int $branchId): float
    {
        $totalRevenue = Invoice::where('branch_id', $branchId)->sum('total');
        $totalExpenses = Expense::where('branch_id', $branchId)->sum('amount');

        return $totalRevenue - $totalExpenses;
    }

    /**
     * الحصول على الإيرادات الشهرية
     */
    private function getMonthlyRevenue(int $branchId): float
    {
        $currentMonth = now()->startOfMonth();

        return Invoice::where('branch_id', $branchId)
            ->where('created_at', '>=', $currentMonth)
            ->sum('total');
    }

    /**
     * الحصول على الفواتير المعلقة
     */
    private function getOutstandingInvoices(int $branchId): float
    {
        return Invoice::where('branch_id', $branchId)
            ->where('payment_status', 'pending')
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
