<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum DeliveryType: string {
    case STANDARD_DELIVERY = 'Standard Delivery';
    case EXPRESS_DELIVERY = 'Express Delivery';
    case PICKUP_POINT = 'Pickup Point';
}