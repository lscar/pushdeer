<?php

namespace App\Http;

enum ReturnCode: string
{
    case SUCCESS = '0';
    case AUTH    = '80403';
    case ARGS    = '80501';
    case REMOTE  = '80502';
    case DEFAULT = '80999';
}