<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\BusinessSettingsServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    App\Providers\ConfigurationServiceProvider::class,

    // Sentry for error tracking and monitoring
    \Sentry\Laravel\ServiceProvider::class,

    // Telescope for debugging (dev-only)
    ...(in_array(config('app.env'), ['local', 'staging']) ? [
        \Laravel\Telescope\TelescopeServiceProvider::class,
        \App\Providers\TelescopeServiceProvider::class,
    ] : []),

    // Log Viewer for viewing application logs
    \Opcodes\LogViewer\LogViewerServiceProvider::class,
];
