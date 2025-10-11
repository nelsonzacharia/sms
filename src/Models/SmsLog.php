<?php

namespace Nelson\Sms\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'recipient',
        'message',
        'status',
        'response_code',
        'response_message',
        'response_body',
        'status_group_id',
        'status_group_name',
        'status_id',
        'status_name',
        'status_description',
    ];

    protected $casts = [
        'to' => 'array',
        'response' => 'array',
    ];
}
