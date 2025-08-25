@extends('layouts.app')

@section('title', 'النموذج غير موجود')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    النموذج غير موجود
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    المورد المطلوب غير موجود في قاعدة البيانات
                </p>
            </div>

            <div class="mt-8 space-y-6">
                @if(config('app.debug'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                تفاصيل الخطأ (للمطورين فقط)
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p><strong>النموذج:</strong> {{ $model }}</p>
                                <p><strong>المعرف:</strong> {{ is_array($id) ? implode(', ', $id) : $id }}</p>
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





