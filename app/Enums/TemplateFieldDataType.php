<?php

namespace App\Enums;

enum TemplateFieldDataType: string
{
    case String = 'string';
    case Date = 'date';
    case Number = 'number';
}