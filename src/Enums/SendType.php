<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;


enum SendType: string {
    case FORWARD = 'FORWARD';
    case BACK = 'BACK';
    case BACK14 = 'BACK14';
    case REPAIR = 'REPAIR';
    case FORWARD_AND_BACK = 'FORWARD_AND_BACK';
    case REPLENISHMENT = 'REPLENISHMENT';
    case TRANSFER = 'TRANSFER';
    case COST_PER_COURIER_QUOTE = 'COST_PER_COURIER_QUOTE';
    case COST_PER_COURIER_QUOTE_AND_FORWARD = 'COST_PER_COURIER_QUOTE_AND_FORWARD';
    case GENERIC = 'GENERIC';
}