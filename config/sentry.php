<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sentry DSN
    |--------------------------------------------------------------------------
    |
    | The DSN tells the Sentry SDK where to send the events to. If this value
    | is not set, the SDK will try to read it from the SENTRY_DSN environment
    | variable. If that variable is also not set, the SDK will just not send
    | any events.
    |
    */

    'dsn' => env('SENTRY_DSN'),

    /*
    |--------------------------------------------------------------------------
    | Sentry Environment
    |--------------------------------------------------------------------------
    |
    | The environment name to be sent with every event (e.g. production, staging).
    |
    */

    'environment' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Sentry Release
    |--------------------------------------------------------------------------
    |
    | The release version to be sent with every event.
    |
    */

    'release' => env('SENTRY_RELEASE'),

    /*
    |--------------------------------------------------------------------------
    | Sentry Traces Sample Rate
    |--------------------------------------------------------------------------
    |
    | The percentage of transactions to capture for performance monitoring.
    | Set this to a value between 0.0 and 1.0, where 1.0 means 100% of
    | transactions are captured.
    |
    */

    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.1),

    /*
    |--------------------------------------------------------------------------
    | Sentry Profiles Sample Rate
    |--------------------------------------------------------------------------
    |
    | The percentage of transactions to capture for profiling.
    | Set this to a value between 0.0 and 1.0, where 1.0 means 100% of
    | transactions are profiled.
    |
    */

    'profiles_sample_rate' => (float) env('SENTRY_PROFILES_SAMPLE_RATE', 0.1),

    /*
    |--------------------------------------------------------------------------
    | Sentry Before Send
    |--------------------------------------------------------------------------
    |
    | A callback that is invoked before an event is sent to Sentry. If this
    | callback returns null, the event will not be sent.
    |
    */

    'before_send' => function ($event) {
        return $event;
    },

    /*
    |--------------------------------------------------------------------------
    | Sentry Before Send Transaction
    |--------------------------------------------------------------------------
    |
    | A callback that is invoked before a transaction is sent to Sentry. If this
    | callback returns null, the transaction will not be sent.
    |
    */

    'before_send_transaction' => function ($transaction) {
        return $transaction;
    },

    /*
    |--------------------------------------------------------------------------
    | Sentry Send Default PII
    |--------------------------------------------------------------------------
    |
    | If this is set to true, certain PII (Personally Identifiable Information)
    | is added by active integrations. By default, no such data is sent.
    |
    */

    'send_default_pii' => false,

    /*
    |--------------------------------------------------------------------------
    | Sentry Max Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | The maximum number of breadcrumbs to keep in memory. Defaults to 100.
    |
    */

    'max_breadcrumbs' => 100,

    /*
    |--------------------------------------------------------------------------
    | Sentry Attach Stacktrace
    |--------------------------------------------------------------------------
    |
    | Whether to attach stack traces to error messages. Defaults to true.
    |
    */

    'attach_stacktrace' => true,

    /*
    |--------------------------------------------------------------------------
    | Sentry Context Lines
    |--------------------------------------------------------------------------
    |
    | The number of context lines to include when attaching stack traces.
    | Defaults to 7.
    |
    */

    'context_lines' => 7,

    /*
    |--------------------------------------------------------------------------
    | Sentry Enable Tracing
    |--------------------------------------------------------------------------
    |
    | Whether to enable performance monitoring. Defaults to true.
    |
    */

    'enable_tracing' => true,
];
