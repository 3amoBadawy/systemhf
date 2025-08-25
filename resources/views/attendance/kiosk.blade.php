@extends('layouts.app')

@section('title', 'كشك الحضور')

@section('page-header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">كشك الحضور</h1>
        <p class="text-gray-600 mt-1">واجهة تسجيل الحضور والانصراف للموظفين</p>
    </div>
    <div class="text-lg font-medium text-blue-600">
        {{ now()->format('Y-m-d H:i') }}
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Kiosk Interface -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">مرحباً بك</h2>
            <p class="text-gray-600">اختر نوع العملية التي تريد تنفيذها</p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Check In -->
            <button onclick="showCheckInModal()" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-8 transition-colors">
                <div class="text-center">
                    <svg class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">تسجيل الحضور</h3>
                    <p class="text-green-100">بدء يوم العمل</p>
                </div>
            </button>

            <!-- Check Out -->
            <button onclick="showCheckOutModal()" class="bg-red-600 hover:bg-red-700 text-white rounded-lg p-8 transition-colors">
                <div class="text-center">
                    <svg class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">تسجيل الانصراف</h3>
                    <p class="text-red-100">انهاء يوم العمل</p>
                </div>
            </button>
        </div>

        <!-- Break Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Start Break -->
            <button onclick="showBreakModal('start')" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg p-6 transition-colors">
                <div class="text-center">
                    <svg class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">بدء الاستراحة</h3>
                </div>
            </button>

            <!-- End Break -->
            <button onclick="showBreakModal('end')" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 transition-colors">
                <div class="text-center">
                    <svg class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">انهاء الاستراحة</h3>
                </div>
            </button>
        </div>
    </div>

    <!-- Today's Status -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">حالة اليوم</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
            <div class="bg-gray-50 rounded p-4">
                <div class="text-2xl font-bold text-gray-900">--:--</div>
                <div class="text-sm text-gray-600">وقت الحضور</div>
            </div>
            <div class="bg-gray-50 rounded p-4">
                <div class="text-2xl font-bold text-gray-900">--:--</div>
                <div class="text-sm text-gray-600">وقت الانصراف</div>
            </div>
            <div class="bg-gray-50 rounded p-4">
                <div class="text-2xl font-bold text-gray-900">0:00</div>
                <div class="text-sm text-gray-600">ساعات العمل</div>
            </div>
            <div class="bg-gray-50 rounded p-4">
                <div class="text-2xl font-bold text-gray-900">0</div>
                <div class="text-sm text-gray-600">دقائق التأخير</div>
            </div>
        </div>
    </div>
</div>

<!-- Check In Modal -->
<div id="checkInModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-900 mb-4">تسجيل الحضور</h3>
            
            <form id="checkInForm" action="{{ route('attendance.check-in') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الموظف</label>
                    <input type="text" name="employee_number" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="أدخل رقم الموظف">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور/PIN</label>
                    <input type="password" name="pin" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="أدخل كلمة المرور">
                </div>
                
                <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                    <button type="button" onclick="hideModal('checkInModal')" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        تسجيل الحضور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Check Out Modal -->
<div id="checkOutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-900 mb-4">تسجيل الانصراف</h3>
            
            <form id="checkOutForm" action="{{ route('attendance.check-out') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الموظف</label>
                    <input type="text" name="employee_number" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="أدخل رقم الموظف">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور/PIN</label>
                    <input type="password" name="pin" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="أدخل كلمة المرور">
                </div>
                
                <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                    <button type="button" onclick="hideModal('checkOutModal')" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        تسجيل الانصراف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCheckInModal() {
    document.getElementById('checkInModal').classList.remove('hidden');
}

function showCheckOutModal() {
    document.getElementById('checkOutModal').classList.remove('hidden');
}

function showBreakModal(type) {
    // Implement break modal logic
    alert(type === 'start' ? 'بدء الاستراحة' : 'انهاء الاستراحة');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Update time every second
setInterval(function() {
    const now = new Date();
    const timeString = now.toLocaleString('ar-SA', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    const timeElements = document.querySelectorAll('.text-lg.font-medium.text-blue-600');
    timeElements.forEach(el => {
        if (el.textContent.includes(':')) {
            el.textContent = timeString;
        }
    });
}, 1000);
</script>
@endsection
