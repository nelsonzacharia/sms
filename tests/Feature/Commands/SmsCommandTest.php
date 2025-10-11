<?php

namespace Nelson\Sms\Tests\Feature\Commands;

use Nelson\Sms\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SmsCommandTest extends TestCase
{
    #[Test]
    public function the_sms_command_works()
    {
        $this->artisan('sms')->assertExitCode(0);
    }

    #[Test]
    public function the_sms_test_url_works()
    {
        $this->artisan('sms')
            ->expectsOutput('https://messaging-service.co.tz/api/sms/v1/test/text/single')
            ->assertExitCode(0);
    }

    #[Test]
    public function the_sms_base_url_set()
    {
        $baseUrl = config()->get('sms.next.base_url');

        $this->assertEquals($baseUrl, 'https://messaging-service.co.tz/api/sms/v1/');
    }
}
