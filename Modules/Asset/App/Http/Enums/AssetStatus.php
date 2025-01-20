<?php

namespace Modules\Asset\App\Http\Enums;

enum AssetStatus: string
{
    case PENDING = 'در انتظار بررسی';
    case APPROVED = 'تایید شده ';
    case REJECTED = 'رد شده';
}
