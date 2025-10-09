<?php

namespace Nelson\Sms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Nelson\Sms\Commands\SmsCommand;

class SmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('sms')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_sms_table')
            ->hasCommand(SmsCommand::class);
    }
}
