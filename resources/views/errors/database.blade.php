@extends('layouts.app')

@section('title', 'خطأ في قاعدة البيانات')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    خطأ في قاعدة البيانات
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    حدث خطأ أثناء الاتصال بقاعدة البيانات
                </p>
            </div>

            <div class="mt-8 space-y-6">
                @if(config('app.debug'))
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                تفاصيل الخطأ (للمطورين فقط)
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p><strong>الخطأ:</strong> {{ $error }}</p>
                                @if(isset($sql))
                                <p><strong>SQL:</strong> <code class="bg-red-100 px-1 rounded">{{ $sql }}</code></p>
                                @endif
                                @if(isset($bindings))
                                <p><strong>المعاملات:</strong> <code class="bg-red-100 px-1 rounded">{{ json_encode($bindings) }}</code></p>
                                @endif
                                <p><strong>الملف:</strong> {{ $file }}:{{ $line }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between">
                    <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-500">
                        <i class="fas fa-arrow-right ml-1"></i>
                        العودة للصفحة السابقة
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        الذهاب للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





