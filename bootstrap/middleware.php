<?php

return [
    'web' => [
        \App\Http\Middleware\ErrorLogger::class,
        \App\Http\Middleware\ActivityLogger::class,
        \App\Http\Middleware\BranchContext::class,
    ],

    'api' => [
        // API middleware
    ],

    'auth' => [
        \App\Http\Middleware\CheckPermission::class,
    ],

    // Middleware للصلاحيات
    'permission' => \App\Http\Middleware\CheckPermission::class,
    'branch.access' => \App\Http\Middleware\CheckBranchAccess::class,
];
