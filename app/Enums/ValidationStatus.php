<?php

namespace App\Enums;

enum ValidationStatus: string
{
    case Valid = 'valid';
    case Invalid = 'invalid';
    case Duplicate = 'duplicate';
    case Warning = 'warning';
}