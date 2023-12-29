<?php

namespace UnknowSk\Core;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use UnknowSk\Core\Commands\CoreCommand;

class CoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-core')
            ->hasConfigFile([
                'app',
                'auth',
                'broadcasting',
                'cache',
                'core',
                'cors',
                'database',
                'filesystems',
                'hashing',
                'logging',
                'mail',
                'permission',
                'queue',
                'sanctum',
                'services',
                'session',
                'tenancy',
                'view',
            ])
            ->hasMigrations([
                '2014_10_12_000000_create_users_table',
                '2014_10_12_100000_create_password_reset_tokens_table',
                '2019_08_19_000000_create_failed_jobs_table',
                '2019_12_14_000001_create_personal_access_tokens_table',
                '2023_12_29_103734_create_permission_tables',
            ])
            ->runsMigrations()
            ->hasCommand(CoreCommand::class);
    }
}
