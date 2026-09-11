<?php

namespace App\Enums;

enum CertificateStatus: string
{
    case Pending = 'pending';
    case Generating = 'generating';
    case Generated = 'generated';
    case Failed = 'failed';
}