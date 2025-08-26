<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()) {
            $this->setBranchContext();
        }

        return $next($request);
    }

    /**
     * تعيين سياق الفرع للمستخدم المسجل
     */
    protected function setBranchContext(): void
    {
        $user = Auth::user();
        $branchInfo = $this->getBranchInfo($user);

        if ($branchInfo) {
            $this->storeBranchInSession($branchInfo);
            $this->shareBranchWithViews($branchInfo);
            $this->applyBranchFilters($branchInfo['id']);
        }
    }

    /**
     * الحصول على معلومات الفرع من المستخدم
     */
    private function getBranchInfo($user): ?array
    {
        if (! $user) {
            return null;
        }

        // الحصول على الفرع من المستخدم مباشرة
        if ($this->hasUserBranch($user)) {
            return [
                'id' => $user->branch_id,
                'branch' => $user->branch ?? null,
            ];
        }

        // الحصول على الفرع من الموظف
        if ($this->hasEmployeeBranch($user)) {
            return [
                'id' => $user->employee->branch_id,
                'branch' => $user->employee->branch ?? null,
            ];
        }

        return null;
    }

    /**
     * التحقق من وجود فرع للمستخدم
     */
    private function hasUserBranch($user): bool
    {
        return property_exists($user, 'branch_id') && $user->branch_id;
    }

    /**
     * التحقق من وجود فرع للموظف
     */
    private function hasEmployeeBranch($user): bool
    {
        if (! $this->hasEmployee($user)) {
            return false;
        }

        return $this->hasEmployeeBranchId($user->employee);
    }

    /**
     * التحقق من وجود موظف للمستخدم
     */
    private function hasEmployee($user): bool
    {
        return property_exists($user, 'employee') && $user->employee;
    }

    /**
     * التحقق من وجود معرف فرع للموظف
     */
    private function hasEmployeeBranchId($employee): bool
    {
        return property_exists($employee, 'branch_id') && $employee->branch_id;
    }

    /**
     * تخزين معلومات الفرع في الجلسة
     */
    private function storeBranchInSession(array $branchInfo): void
    {
        $branch = $branchInfo['branch'];
        session([
            'current_branch_id' => $branchInfo['id'],
            'current_branch_name' => $branch->display_name,
            'current_branch_code' => $branch->code,
        ]);
    }

    /**
     * مشاركة معلومات الفرع مع الواجهات
     */
    private function shareBranchWithViews(array $branchInfo): void
    {
        $branch = $branchInfo['branch'];
        View::share([
            'currentBranch' => $branch,
            'currentBranchId' => $branchInfo['id'],
            'currentBranchName' => $branch->display_name,
        ]);
    }

    /**
     * تطبيق فلاتر الفرع على الاستعلامات
     */
    protected function applyBranchFilters(int $branchId): void
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
