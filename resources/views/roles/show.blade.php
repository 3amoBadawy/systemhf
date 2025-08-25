@extends('layouts.app')

@section('title', 'عرض الدور')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- رأس الصفحة -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $role->name_ar }}</h1>
                    <p class="mt-2 text-gray-600">{{ $role->description ?: 'لا يوجد وصف للدور' }}</p>
                </div>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <a href="{{ route('roles.edit', $role) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل
                    </a>
                    <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للأدوار
                    </a>
                </div>
            </div>
        </div>

        <!-- معلومات الدور -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- معلومات أساسية -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">معلومات الدور</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">اسم الدور (إنجليزي)</span>
                            <span class="text-sm text-gray-900">{{ $role->name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">اسم الدور (عربي)</span>
                            <span class="text-sm text-gray-900">{{ $role->name_ar }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">الوصف</span>
                            <span class="text-sm text-gray-900">{{ $role->description ?: 'لا يوجد وصف' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">الفرع</span>
                            <span class="text-sm text-gray-900">
                                @if($role->branch)
                                    {{ $role->branch->name_ar }}
                                @else
                                    <span class="text-gray-400">غير محدد</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">النوع</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                @if($role->is_system)
                                    <span class="bg-blue-100 text-blue-800">
                                        <i class="fas fa-shield-alt ml-1"></i>
                                        نظامي
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-800">
                                        <i class="fas fa-user-edit ml-1"></i>
                                        مخصص
                                    </span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">تاريخ الإنشاء</span>
                            <span class="text-sm text-gray-900">{{ $role->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm font-medium text-gray-500">آخر تحديث</span>
                            <span class="text-sm text-gray-900">{{ $role->updated_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">إحصائيات سريعة</h2>
                    
                    <div class="space-y-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $role->permissions->count() }}</div>
                            <div class="text-sm text-blue-800">الصلاحيات</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $role->users->count() }}</div>
                            <div class="text-sm text-green-800">المستخدمون</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $role->employees->count() }}</div>
                            <div class="text-sm text-purple-800">الموظفون</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الصلاحيات -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">الصلاحيات الممنوحة</h2>
                    <span class="text-sm text-gray-500">{{ $role->permissions->count() }} صلاحية</span>
                </div>
                
                @if($role->permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($role->permissions as $permission)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $permission->name_ar }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $permission->name }}</p>
                                        @if($permission->description)
                                            <p class="text-xs text-gray-400 mt-2">{{ $permission->description }}</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check ml-1"></i>
                                        مفعلة
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                        <p class="text-lg">لا توجد صلاحيات ممنوحة لهذا الدور</p>
                        <p class="text-sm mt-1">قم بتعديل الدور لإضافة صلاحيات</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- المستخدمون والموظفون -->
        @if($role->users->count() > 0 || $role->employees->count() > 0)
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">المستخدمون والموظفون</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- المستخدمون -->
                    @if($role->users->count() > 0)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">المستخدمون ({{ $role->users->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($role->users as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $user->created_at->format('Y-m-d') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- الموظفون -->
                    @if($role->employees->count() > 0)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">الموظفون ({{ $role->employees->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($role->employees as $employee)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-tie text-green-600 text-sm"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->name_ar }}</div>
                                            <div class="text-xs text-gray-500">{{ $employee->position }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $employee->created_at->format('Y-m-d') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection





