<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\SystemSetting;
use App\Models\BusinessSetting;

class SystemConfigurationService
{
    /**
     * Get all system configuration grouped by category
     */
    public function getAllConfiguration(): array
    {
        return [
            'system_settings' => $this->getSystemSettings(),
            'business_settings' => $this->getBusinessSettings(),
            'environment_variables' => $this->getEnvironmentVariables(),
            'laravel_config' => $this->getLaravelConfig(),
            'database_config' => $this->getDatabaseConfig(),
            'mail_config' => $this->getMailConfig(),
            'cache_config' => $this->getCacheConfig(),
            'queue_config' => $this->getQueueConfig(),
            'session_config' => $this->getSessionConfig(),
            'logging_config' => $this->getLoggingConfig(),
            'file_storage_config' => $this->getFileStorageConfig(),
            'security_config' => $this->getSecurityConfig(),
            'performance_config' => $this->getPerformanceConfig(),
        ];
    }

    /**
     * Get system settings from database
     */
    public function getSystemSettings(): array
    {
        $settings = SystemSetting::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $category = $setting->category ?? 'general';
            if (!isset($result[$category])) {
                $result[$category] = [];
            }
            
            $result[$category][] = [
                'key' => $setting->key,
                'value' => $this->castValue($setting->value, $setting->type),
                'type' => $setting->type,
                'description' => $setting->description_ar ?? $setting->description_en ?? $setting->key,
                'is_editable' => $setting->is_editable,
                'requires_restart' => $setting->requires_restart ?? false,
                'source' => 'database',
            ];
        }
        
        return $result;
    }

    /**
     * Get business settings from database
     */
    public function getBusinessSettings(): array
    {
        $settings = BusinessSetting::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[] = [
                'key' => $setting->key ?? $setting->getTable() . '_' . $setting->id,
                'value' => $setting->value ?? $setting->getRawOriginal(),
                'type' => $this->determineType($setting->value ?? $setting->getRawOriginal()),
                'description' => $this->getBusinessSettingDescription($setting),
                'is_editable' => true,
                'requires_restart' => false,
                'source' => 'database',
            ];
        }
        
        return $result;
    }

    /**
     * Get environment variables
     */
    public function getEnvironmentVariables(): array
    {
        $envVars = [
            'APP_ENV' => 'Environment (local, production, staging)',
            'APP_DEBUG' => 'Debug mode (true/false)',
            'APP_URL' => 'Application URL',
            'APP_TIMEZONE' => 'Application timezone',
            'APP_LOCALE' => 'Application locale',
            'APP_FALLBACK_LOCALE' => 'Fallback locale',
            'APP_KEY' => 'Application encryption key',
            'DB_CONNECTION' => 'Database connection type',
            'DB_HOST' => 'Database host',
            'DB_PORT' => 'Database port',
            'DB_DATABASE' => 'Database name',
            'DB_USERNAME' => 'Database username',
            'CACHE_DRIVER' => 'Cache driver (file, redis, memcached)',
            'SESSION_DRIVER' => 'Session driver (file, redis, database)',
            'QUEUE_CONNECTION' => 'Queue connection (sync, database, redis)',
            'MAIL_MAILER' => 'Mail driver (smtp, log, array)',
            'LOG_CHANNEL' => 'Log channel (stack, single, daily)',
            'BROADCAST_DRIVER' => 'Broadcast driver (pusher, redis, log)',
            'FILESYSTEM_DISK' => 'File system disk (local, s3, ftp)',
        ];

        $result = [];
        foreach ($envVars as $key => $description) {
            $value = env($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->maskSensitiveValue($key, $value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false, // Environment variables are read-only
                    'requires_restart' => true,
                    'source' => 'environment',
                ];
            }
        }

        return $result;
    }

    /**
     * Get Laravel configuration
     */
    public function getLaravelConfig(): array
    {
        $configs = [
            'app.name' => 'Application name',
            'app.env' => 'Application environment',
            'app.debug' => 'Debug mode',
            'app.url' => 'Application URL',
            'app.timezone' => 'Application timezone',
            'app.locale' => 'Application locale',
            'app.fallback_locale' => 'Fallback locale',
            'app.key' => 'Application key',
            'app.cipher' => 'Encryption cipher',
            'app.providers' => 'Service providers',
            'app.aliases' => 'Facade aliases',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->formatConfigValue($value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'laravel_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get database configuration
     */
    public function getDatabaseConfig(): array
    {
        $configs = [
            'database.default' => 'Default database connection',
            'database.connections.mysql.host' => 'MySQL host',
            'database.connections.mysql.port' => 'MySQL port',
            'database.connections.mysql.database' => 'MySQL database name',
            'database.connections.mysql.username' => 'MySQL username',
            'database.connections.mysql.charset' => 'MySQL charset',
            'database.connections.mysql.collation' => 'MySQL collation',
            'database.connections.mysql.prefix' => 'MySQL table prefix',
            'database.connections.mysql.strict' => 'MySQL strict mode',
            'database.connections.mysql.engine' => 'MySQL engine',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->maskSensitiveValue($key, $value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'database_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get mail configuration
     */
    public function getMailConfig(): array
    {
        $configs = [
            'mail.default' => 'Default mailer',
            'mail.mailers.smtp.host' => 'SMTP host',
            'mail.mailers.smtp.port' => 'SMTP port',
            'mail.mailers.smtp.username' => 'SMTP username',
            'mail.mailers.smtp.password' => 'SMTP password',
            'mail.mailers.smtp.encryption' => 'SMTP encryption',
            'mail.mailers.smtp.timeout' => 'SMTP timeout',
            'mail.from.address' => 'From email address',
            'mail.from.name' => 'From name',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->maskSensitiveValue($key, $value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'mail_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get cache configuration
     */
    public function getCacheConfig(): array
    {
        $configs = [
            'cache.default' => 'Default cache driver',
            'cache.stores.file.driver' => 'File cache driver',
            'cache.stores.redis.connection' => 'Redis connection',
            'cache.stores.redis.host' => 'Redis host',
            'cache.stores.redis.port' => 'Redis port',
            'cache.stores.redis.password' => 'Redis password',
            'cache.stores.redis.database' => 'Redis database',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->maskSensitiveValue($key, $value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'cache_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get queue configuration
     */
    public function getQueueConfig(): array
    {
        $configs = [
            'queue.default' => 'Default queue connection',
            'queue.connections.database.table' => 'Queue table name',
            'queue.connections.database.queue' => 'Default queue name',
            'queue.connections.database.retry_after' => 'Retry after seconds',
            'queue.connections.redis.connection' => 'Redis connection',
            'queue.connections.redis.queue' => 'Redis queue name',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->formatConfigValue($value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'queue_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get session configuration
     */
    public function getSessionConfig(): array
    {
        $configs = [
            'session.driver' => 'Session driver',
            'session.lifetime' => 'Session lifetime (minutes)',
            'session.expire_on_close' => 'Expire on close',
            'session.encrypt' => 'Encrypt session data',
            'session.files' => 'Session files path',
            'session.connection' => 'Session database connection',
            'session.table' => 'Session table name',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->formatConfigValue($value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'session_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get logging configuration
     */
    public function getLoggingConfig(): array
    {
        $configs = [
            'logging.default' => 'Default log channel',
            'logging.channels.stack.channels' => 'Stack channels',
            'logging.channels.single.path' => 'Single log file path',
            'logging.channels.daily.path' => 'Daily log file path',
            'logging.channels.daily.days' => 'Log retention days',
            'logging.channels.slack.url' => 'Slack webhook URL',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->formatConfigValue($value),
                    'source' => 'logging_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get file storage configuration
     */
    public function getFileStorageConfig(): array
    {
        $configs = [
            'filesystems.default' => 'Default disk',
            'filesystems.disks.local.root' => 'Local disk root',
            'filesystems.disks.s3.key' => 'S3 access key',
            'filesystems.disks.s3.secret' => 'S3 secret key',
            'filesystems.disks.s3.region' => 'S3 region',
            'filesystems.disks.s3.bucket' => 'S3 bucket name',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->maskSensitiveValue($key, $value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'file_storage_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get security configuration
     */
    public function getSecurityConfig(): array
    {
        $configs = [
            'auth.defaults.guard' => 'Default authentication guard',
            'auth.guards.web.provider' => 'Web guard provider',
            'auth.providers.users.model' => 'User model',
            'auth.providers.users.table' => 'Users table',
            'sanctum.stateful' => 'Sanctum stateful domains',
            'cors.allowed_origins' => 'CORS allowed origins',
            'cors.allowed_methods' => 'CORS allowed methods',
            'cors.allowed_headers' => 'CORS allowed headers',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->formatConfigValue($value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'security_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Get performance configuration
     */
    public function getPerformanceConfig(): array
    {
        $configs = [
            'cache.ttl' => 'Cache TTL (seconds)',
            'session.gc_maxlifetime' => 'Session garbage collection lifetime',
            'queue.retry_after' => 'Queue retry after (seconds)',
            'mail.encryption' => 'Mail encryption',
            'database.connections.mysql.options' => 'MySQL options',
        ];

        $result = [];
        foreach ($configs as $key => $description) {
            $value = config($key);
            if ($value !== null) {
                $result[] = [
                    'key' => $key,
                    'value' => $this->formatConfigValue($value),
                    'type' => $this->determineType($value),
                    'description' => $description,
                    'is_editable' => false,
                    'requires_restart' => true,
                    'source' => 'performance_config',
                ];
            }
        }

        return $result;
    }

    /**
     * Cast value to appropriate type
     */
    private function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
            case 'bool':
                return (bool) $value;
            case 'integer':
            case 'int':
                return (int) $value;
            case 'decimal':
            case 'float':
            case 'double':
                return (float) $value;
            case 'json':
            case 'array':
                return is_string($value) ? json_decode($value, true) : $value;
            default:
                return $value;
        }
    }

    /**
     * Determine the type of a value
     */
    private function determineType($value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_int($value)) return 'integer';
        if (is_float($value)) return 'decimal';
        if (is_array($value)) return 'json';
        if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) return 'json';
        return 'string';
    }

    /**
     * Mask sensitive values
     */
    private function maskSensitiveValue(string $key, $value): string
    {
        $sensitiveKeys = ['password', 'secret', 'key', 'token', 'auth'];
        foreach ($sensitiveKeys as $sensitive) {
            if (stripos($key, $sensitive) !== false) {
                return str_repeat('*', min(strlen($value), 8));
            }
        }
        return (string) $value;
    }

    /**
     * Format configuration value for display
     */
    private function formatConfigValue($value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        return (string) $value;
    }

    /**
     * Get business setting description
     */
    private function getBusinessSettingDescription($setting): string
    {
        $descriptions = [
            'business_name' => 'Business name in English',
            'business_name_ar' => 'Business name in Arabic',
            'default_profit_percent' => 'Default profit percentage',
            'currency' => 'Default currency',
            'currency_symbol' => 'Currency symbol',
            'currency_symbol_placement' => 'Currency symbol placement',
            'timezone' => 'Business timezone',
            'logo' => 'Business logo',
            'date_format' => 'Date format',
            'time_format' => 'Time format',
            'phone' => 'Business phone',
            'email' => 'Business email',
            'address' => 'Business address',
            'description' => 'Business description',
        ];

        $key = $setting->key ?? $setting->getTable() . '_' . $setting->id;
        return $descriptions[$key] ?? $key;
    }
}
