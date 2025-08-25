<?php

namespace App\Http\Middleware;

use App\Services\ConfigurationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class BranchContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $this->setBranchContext($request);
        }

        return $next($request);
    }

    /**
     * تعيين سياق الفرع للمستخدم المسجل
     */
    protected function setBranchContext(Request $request): void
    {
        $user = auth()->user();
        $branch = null;
        $branchId = null;

        // الحصول على الفرع من المستخدم أو الموظف
        if ($user->branch_id) {
            $branch = $user->branch;
            $branchId = $user->branch_id;
        } elseif ($user->employee && $user->employee->branch_id) {
            $branch = $user->employee->branch;
            $branchId = $user->employee->branch_id;
        }

        // تخزين معلومات الفرع في الجلسة
        if ($branch) {
            session([
                'current_branch_id' => $branchId,
                'current_branch_name' => $branch->display_name,
                'current_branch_code' => $branch->code,
            ]);

            // مشاركة معلومات الفرع مع الواجهات
            View::share([
                'currentBranch' => $branch,
                'currentBranchId' => $branchId,
                'currentBranchName' => $branch->display_name,
            ]);

            // تحميل إعدادات الفرع
            $this->loadBranchSettings($branchId);

            // تطبيق فلاتر الفرع على الاستعلامات
            $this->applyBranchFilters($branchId);
        }
    }

    /**
     * تحميل إعدادات الفرع
     */
    protected function loadBranchSettings($branchId): void
    {
        try {
            $branchSettings = ConfigurationService::getCurrentBranchSettings();

            // مشاركة الإعدادات مع الواجهات
            View::share('branchSettings', $branchSettings);

            // تطبيق إعدادات الفرع على التطبيق
            $this->applyBranchSettings($branchSettings);

        } catch (\Exception $e) {
            \Log::error('Failed to load branch settings: '.$e->getMessage());
        }
    }

    /**
     * تطبيق إعدادات الفرع على التطبيق
     */
    protected function applyBranchSettings(array $settings): void
    {
        // تطبيق إعدادات العملة
        if (isset($settings['currency_symbol'])) {
            config(['app.currency_symbol' => $settings['currency_symbol']]);
        }

        // تطبيق إعدادات الوقت
        if (isset($settings['working_hours_start'])) {
            config(['business.working_hours_start' => $settings['working_hours_start']]);
        }

        if (isset($settings['working_hours_end'])) {
            config(['business.working_hours_end' => $settings['working_hours_end']]);
        }

        // تطبيق إعدادات الضرائب
        if (isset($settings['tax_rate'])) {
            config(['business.tax_rate' => $settings['tax_rate']]);
        }
    }

    /**
     * تطبيق فلاتر الفرع على الاستعلامات
     */
    protected function applyBranchFilters($branchId): void
    {
        // يمكن إضافة Global Scopes هنا لتطبيق فلاتر الفرع تلقائياً
        // على النماذج التي تحتوي على branch_id

        // مثال: تطبيق فلتر الفرع على استعلامات العملاء
        \App\Models\Customer::addGlobalScope('branch', function ($builder) use ($branchId) {
            $builder->where('branch_id', $branchId);
        });

        // تطبيق فلتر الفرع على استعلامات المنتجات
        \App\Models\Product::addGlobalScope('branch', function ($builder) use ($branchId) {
            $builder->where('branch_id', $branchId);
        });

        // تطبيق فلتر الفرع على استعلامات الفواتير
        \App\Models\Invoice::addGlobalScope('branch', function ($builder) use ($branchId) {
            $builder->where('branch_id', $branchId);
        });

        // تطبيق فلتر الفرع على استعلامات المدفوعات
        \App\Models\Payment::addGlobalScope('branch', function ($builder) use ($branchId) {
            $builder->where('branch_id', $branchId);
        });

        // تطبيق فلتر الفرع على استعلامات الموظفين
        \App\Models\Employee::addGlobalScope('branch', function ($builder) use ($branchId) {
            $builder->where('branch_id', $branchId);
        });
    }
}
