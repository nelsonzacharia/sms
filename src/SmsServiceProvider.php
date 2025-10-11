<?php

namespace Nelson\Sms;

use Nelson\Sms\Commands\SmsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigration('create_sms_logs_table')
            ->hasCommand(SmsCommand::class);
    }


    public function packageRegistered(): void
    {
        // Bind the SMS service to the container
        $this->app->singleton(Sms::class, function ($app) {
            return new Sms(config('sms'));
        });
    }

//    public function register()
//    {
//        $this->mergeConfigFrom(__DIR__.'/../config/sms.php', 'sms');
//
//        $this->app->singleton(SmsManager::class, function ($app) {
//            return new SmsManager(
//                config('sms.base_url'),
//                config('sms.api_key'),
//                config('sms.sender_id'),
//                config('sms.log_enabled')
//            );
//        });
//    }
}
