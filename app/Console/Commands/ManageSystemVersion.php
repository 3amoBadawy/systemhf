<?php

namespace App\Console\Commands;

use App\Services\SystemVersionService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ManageSystemVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:version 
                            {action : Action to perform (create, update, delete, show, list, set-current)}
                            {--version= : Version number (e.g., 1.0.0)}
                            {--description= : Version description}
                            {--release-date= : Release date (YYYY-MM-DD)}
                            {--id= : Version ID for update/delete operations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage system versions';

    protected SystemVersionService $systemVersionService;

    public function __construct(SystemVersionService $systemVersionService)
    {
        parent::__construct();
        $this->systemVersionService = $systemVersionService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');
        if (! is_string($action)) {
            $this->error('Invalid action argument');

            return 1;
        }

        try {
            switch ($action) {
                case 'create':
                    return $this->createVersion();
                case 'update':
                    return $this->updateVersion();
                case 'delete':
                    return $this->deleteVersion();
                case 'show':
                    return $this->showCurrentVersion();
                case 'list':
                    return $this->listVersions();
                case 'set-current':
                    return $this->setAsCurrent();
                default:
                    $this->error("Unknown action: {$action}");
                    $this->info('Available actions: create, update, delete, show, list, set-current');

                    return 1;
            }
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());

            return 1;
        }
    }

    /**
     * Create a new version
     */
    private function createVersion(): int
    {
        $version = $this->getStringOption('version');
        $description = $this->getStringOption('description');
        $releaseDate = $this->getStringOption('release-date');

        if (! $version) {
            $version = $this->ask('Enter version number (e.g., 1.0.0):');
        }

        if (! $description) {
            $description = $this->ask('Enter version description:');
        }

        if (! $releaseDate) {
            $releaseDate = Carbon::now()->format('Y-m-d');
        }

        // Validate version format
        if (! preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            $this->error('Invalid version format. Use format: X.Y.Z (e.g., 1.0.0)');

            return 1;
        }

        // Validate date format
        try {
            $parsedDate = Carbon::parse($releaseDate);
        } catch (\Exception $e) {
            $this->error('Invalid date format. Use format: YYYY-MM-DD');

            return 1;
        }

        $data = [
            'version' => $version,
            'description' => $description,
            'release_date' => $parsedDate,
            'is_current' => false,
        ];

        if ($this->systemVersionService->createVersion($data)) {
            $this->info("Version {$version} created successfully!");

            return 0;
        } else {
            $this->error('Failed to create version');

            return 1;
        }
    }

    /**
     * Update an existing version
     */
    private function updateVersion(): int
    {
        $id = $this->getIntOption('id');
        $version = $this->getStringOption('version');
        $description = $this->getStringOption('description');
        $releaseDate = $this->getStringOption('release-date');

        if (! $id) {
            $id = (int) $this->ask('Enter version ID to update:');
        }

        $data = [];

        if ($version) {
            if (! preg_match('/^\d+\.\d+\.\d+$/', $version)) {
                $this->error('Invalid version format. Use format: X.Y.Z (e.g., 1.0.0)');

                return 1;
            }
            $data['version'] = $version;
        }

        if ($description) {
            $data['description'] = $description;
        }

        if ($releaseDate) {
            try {
                $parsedDate = Carbon::parse($releaseDate);
                $data['release_date'] = $parsedDate;
            } catch (\Exception $e) {
                $this->error('Invalid date format. Use format: YYYY-MM-DD');

                return 1;
            }
        }

        if (empty($data)) {
            $this->error('No data provided for update');

            return 1;
        }

        if ($this->systemVersionService->updateVersion($id, $data)) {
            $this->info("Version ID {$id} updated successfully!");

            return 0;
        } else {
            $this->error('Failed to update version');

            return 1;
        }
    }

    /**
     * Delete a version
     */
    private function deleteVersion(): int
    {
        $id = $this->getIntOption('id');

        if (! $id) {
            $id = (int) $this->ask('Enter version ID to delete:');
        }

        if ($this->confirm("Are you sure you want to delete version ID {$id}?")) {
            if ($this->systemVersionService->deleteVersion($id)) {
                $this->info("Version ID {$id} deleted successfully!");

                return 0;
            } else {
                $this->error('Failed to delete version');

                return 1;
            }
        }

        $this->info('Operation cancelled');

        return 0;
    }

    /**
     * Show current version
     */
    private function showCurrentVersion(): int
    {
        $currentVersion = $this->systemVersionService->getCurrentVersion();

        if ($currentVersion) {
            $this->info("Current system version: {$currentVersion}");
        } else {
            $this->info('No current version set');
        }

        return 0;
    }

    /**
     * List all versions
     */
    private function listVersions(): int
    {
        $versions = $this->systemVersionService->getAllVersions();

        if ($versions->isEmpty()) {
            $this->info('No versions found');

            return 0;
        }

        $this->info('System Versions:');
        $this->info('----------------');

        foreach ($versions as $version) {
            $status = $version->is_current ? ' (Current)' : '';
            $this->info("ID: {$version->id}, Version: {$version->version}{$status}");
            $this->info("Description: {$version->description}");
            $this->info("Release Date: {$version->release_date}");
            $this->info('----------------');
        }

        return 0;
    }

    /**
     * Set a version as current
     */
    private function setAsCurrent(): int
    {
        $id = $this->getIntOption('id');

        if (! $id) {
            $id = (int) $this->ask('Enter version ID to set as current:');
        }

        if ($this->systemVersionService->setAsCurrent($id)) {
            $this->info("Version ID {$id} set as current successfully!");

            return 0;
        } else {
            $this->error('Failed to set version as current');

            return 1;
        }
    }

    /**
     * Get string option safely
     */
    private function getStringOption(string $name): ?string
    {
        $value = $this->option($name);
        if (is_string($value)) {
            return $value;
        }

        return null;
    }

    /**
     * Get int option safely
     */
    private function getIntOption(string $name): ?int
    {
        $value = $this->option($name);
        if (is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }
}
