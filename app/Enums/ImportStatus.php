<?php

namespace App\Enums;

enum ImportStatus: string
{
    case Uploaded = 'uploaded';
    case Mapped = 'mapped';
    case Validated = 'validated';
    case Confirmed = 'confirmed';
    case Failed = 'failed';
}