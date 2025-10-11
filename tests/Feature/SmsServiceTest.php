<?php

namespace Nelson\Sms\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Nelson\Sms\Sms;
use Nelson\Sms\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SmsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Sms $smsService;

    protected function setUp(): void
    {
        parent::setUp();

        config(['sms.test_mode' => true]);

        $this->artisan('migrate');
        config(['sms.next.log_enabled' => false]);
        $this->smsService = new Sms(config('sms.next'));
    }

    #[Test]
    public function it_sends_single_sms_using_test_api()
    {

        $response = $this->smsService->sendTestSingle('255716718040', 'Test message');
        Log::info('SMS API Request', [
            'response' => $response,
        ]);

        $this->assertEquals('success', $response['status']);
        //        $this->assertDatabaseHas('sms_logs', [
        //            'recipient' => '255716718040',
        //            'status' => 'success',
        //        ]);
    }

    #[Test]
    public function it_sends_multiple_sms_using_test_api()
    {

        $response = $this->smsService->sendMultipleDestinationTest(['255655912841', '255716718040'], 'Test message');
        $this->assertEquals('success', $response['status']);
        //        $this->assertDatabaseCount('sms_logs', 2);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
