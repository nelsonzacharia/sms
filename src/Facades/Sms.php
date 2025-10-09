<?php

namespace Nelson\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nelson\Sms\Sms
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nelson\Sms\Sms::class;
    }
}
