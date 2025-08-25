<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <!-- Business Info -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="شعار الأعمال" class="h-8 w-auto">
                @endif
                <div class="text-sm text-gray-600">
                    <p class="font-medium text-gray-900">{{ $businessName }}</p>
                    <p class="text-xs text-gray-500">{{ $businessNameEn }}</p>
                </div>
            </div>

            <!-- System Version Info -->
            <div class="flex items-center space-x-6 rtl:space-x-reverse text-sm text-gray-600">
                @if($currentVersion)
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium text-gray-900">الإصدار {{ $currentVersion->version }}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $currentVersion->type_badge_class }}">
                            {{ $currentVersion->type_name_ar }}
                        </span>
                    </div>
                    
                    <div class="text-xs text-gray-500">
                        <span>تاريخ الإصدار: {{ $currentVersion->formatted_release_date }}</span>
                    </div>
                @else
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-500">معلومات الإصدار غير متوفرة</span>
                    </div>
                @endif
            </div>

            <!-- Copyright & Links -->
            <div class="flex items-center space-x-6 rtl:space-x-reverse text-sm text-gray-600">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} {{ $businessName }}. جميع الحقوق محفوظة.</p>
                    <p class="text-xs text-gray-500 mt-1">تم التطوير بواسطة فريق التطوير</p>
                </div>
                
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <a href="{{ route('system-settings.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>
                    
                    <a href="{{ route('system.status') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Version Details (Collapsible) -->
        @if($currentVersion)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <details class="group">
                    <summary class="flex items-center justify-center cursor-pointer text-sm text-gray-600 hover:text-gray-900">
                        <span class="mr-2">تفاصيل الإصدار {{ $currentVersion->version }}</span>
                        <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <!-- Features -->
                        @if($currentVersion->features && count($currentVersion->features) > 0)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="h-4 w-4 text-green-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    الميزات الجديدة ({{ count($currentVersion->features) }})
                                </h4>
                                <ul class="space-y-1 text-gray-600">
                                    @foreach(array_slice($currentVersion->features, 0, 5) as $feature)
                                        <li class="flex items-start">
                                            <svg class="h-3 w-3 text-green-500 ml-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                    @if(count($currentVersion->features) > 5)
                                        <li class="text-gray-500 text-xs">و {{ count($currentVersion->features) - 5 }} ميزة أخرى...</li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                        <!-- Bug Fixes -->
                        @if($currentVersion->bug_fixes && count($currentVersion->bug_fixes) > 0)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="h-4 w-4 text-blue-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    الإصلاحات ({{ count($currentVersion->bug_fixes) }})
                                </h4>
                                <ul class="space-y-1 text-gray-600">
                                    @foreach(array_slice($currentVersion->bug_fixes, 0, 5) as $fix)
                                        <li class="flex items-start">
                                            <svg class="h-3 w-3 text-blue-500 ml-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $fix }}
                                        </li>
                                    @endforeach
                                    @if(count($currentVersion->bug_fixes) > 5)
                                        <li class="text-gray-500 text-xs">و {{ count($currentVersion->bug_fixes) - 5 }} إصلاح آخر...</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>

                    @if($currentVersion->description)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $currentVersion->description }}</p>
                        </div>
                    @endif
                </details>
            </div>
        @endif
    </div>
</footer>
