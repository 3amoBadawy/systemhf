@extends('layouts.app')

@section('title', 'إدارة الرواتب')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- رأس الصفحة -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إدارة الرواتب</h1>
                    <p class="mt-2 text-gray-600">إدارة رواتب الموظفين والمدفوعات</p>
                </div>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <a href="{{ route('salary.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة راتب جديد
                    </a>
                    <a href="{{ route('salary.report') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-chart-bar ml-2"></i>
                        تقرير الرواتب
                    </a>
                </div>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي الموظفين</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalEmployees ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي الرواتب</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalSalaries ?? 0, 2) }} ريال</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">الشهر الحالي</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ now()->format('M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-purple-600"></i>
                        </div>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">الرواتب المدفوعة</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $paidSalaries ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- أدوات البحث والفلترة -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <input type="text" id="searchSalary" placeholder="البحث في الرواتب..." 
                           class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <select id="filterMonth" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الأشهر</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                    <select id="filterYear" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع السنوات</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                    <select id="filterStatus" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="paid">مدفوع</option>
                        <option value="pending">معلق</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <button onclick="exportSalaries()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-download ml-2"></i>
                        تصدير
                    </button>
                    <button onclick="printSalaries()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-print ml-2"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>

        <!-- جدول الرواتب -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600">
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموظف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الشهر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب الأساسي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البدلات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخصومات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">صافي الراتب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="salaryTableBody">
                        @if(isset($salaries) && $salaries->count() > 0)
                            @foreach($salaries as $salary)
                                <tr class="salary-row hover:bg-gray-50" data-salary-id="{{ $salary->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="salary-checkbox rounded border-gray-300 text-blue-600" value="{{ $salary->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $salary->employee->name_ar ?? 'غير محدد' }}</div>
                                                <div class="text-sm text-gray-500">{{ $salary->employee->position ?? 'غير محدد' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $salary->month_name ?? 'غير محدد' }} {{ $salary->year ?? 'غير محدد' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($salary->basic_salary ?? 0, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($salary->allowances ?? 0, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($salary->deductions ?? 0, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($salary->net_salary ?? 0, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(isset($salary->status))
                                            @if($salary->status == 'paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check ml-1"></i>
                                                    مدفوع
                                                </span>
                                            @elseif($salary->status == 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock ml-1"></i>
                                                    معلق
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times ml-1"></i>
                                                    ملغي
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <a href="{{ route('salary.show', $salary) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('salary.edit', $salary) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteSalary({{ $salary->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-lg">لا توجد رواتب مسجلة</p>
                                        <p class="text-sm mt-1">قم بإضافة راتب جديد للبدء</p>
                                        <a href="{{ route('salary.create') }}" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                            <i class="fas fa-plus ml-2"></i>
                                            إضافة راتب جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($salaries) && $salaries->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $salaries->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديد الكل
    const selectAll = document.getElementById('selectAll');
    const salaryCheckboxes = document.querySelectorAll('.salary-checkbox');
    
    selectAll.addEventListener('change', function() {
        salaryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // البحث
    const searchInput = document.getElementById('searchSalary');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.salary-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // فلترة الشهر
    const monthFilter = document.getElementById('filterMonth');
    monthFilter.addEventListener('change', function() {
        filterSalaries();
    });
    
    // فلترة السنة
    const yearFilter = document.getElementById('filterYear');
    yearFilter.addEventListener('change', function() {
        filterSalaries();
    });
    
    // فلترة الحالة
    const statusFilter = document.getElementById('filterStatus');
    statusFilter.addEventListener('change', function() {
        filterSalaries();
    });
});

function filterSalaries() {
    const month = document.getElementById('filterMonth').value;
    const year = document.getElementById('filterYear').value;
    const status = document.getElementById('filterStatus').value;
    
    // هنا يمكن إضافة منطق الفلترة
    console.log('Filtering by:', { month, year, status });
}

function deleteSalary(salaryId) {
    if (confirm('هل أنت متأكد من حذف هذا الراتب؟')) {
        // هنا يمكن إضافة منطق الحذف
        console.log('Deleting salary:', salaryId);
    }
}

function exportSalaries() {
    // هنا يمكن إضافة منطق التصدير
    console.log('Exporting salaries...');
}

function printSalaries() {
    // هنا يمكن إضافة منطق الطباعة
    window.print();
}
</script>
@endpush
@endsection





