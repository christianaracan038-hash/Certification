<?php

namespace App\Enums;

enum DeliveryAttemptStatus: string
{
    case Sent = 'sent';
    case Failed = 'failed';
}