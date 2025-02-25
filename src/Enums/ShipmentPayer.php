<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum ShipmentPayer: string {
    case SENDER = 'SENDER';
    case RECEIVER = 'RECEIVER';

}