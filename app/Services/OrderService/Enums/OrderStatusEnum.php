<?php

namespace App\Services\OrderService\Enums;

enum OrderStatusEnum: int
{
    case DRAFT = 0;
    case IN_PROCESSING = 1;
    case DONE = 2;
    case CANCELLED = 3;
}
