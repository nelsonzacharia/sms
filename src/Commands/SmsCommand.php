<?php

namespace Nelson\Sms\Commands;

use Illuminate\Console\Command;

class SmsCommand extends Command
{
    public $signature = 'sms';

    public $description = 'My command';

    public function handle(): int
    {
        $testUrlSingle = config('sms.next.test_single_url');
        $this->comment($testUrlSingle);

        return self::SUCCESS;
    }
}
