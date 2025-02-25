<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum PaymentType: string {
    case CASH = 'CASH';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PAYMENT_ORDER = 'PAYMENT_ORDER';
}