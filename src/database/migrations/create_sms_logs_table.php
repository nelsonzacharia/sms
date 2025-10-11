<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient');
            $table->text('message')->nullable();
            $table->string('status')->nullable();
            $table->integer('response_code')->nullable();
            $table->string('response_message')->nullable();
            $table->json('response_body')->nullable();
            $table->integer('status_group_id')->nullable();
            $table->string('status_group_name')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('status_name')->nullable();
            $table->string('status_description')->nullable();
            $table->timestamps();

        });

    }

    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
};

// composer dump-autoload
// php artisan vendor:publish --provider="Nelson\Sms\SmsServiceProvider" --tag=config
// php artisan vendor:publish --provider="Nelson\Sms\SmsServiceProvider" --tag=migrations
// php artisan migrate
