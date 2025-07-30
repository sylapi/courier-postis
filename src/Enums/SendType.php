<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;


class SendType {
    public const FORWARD = 'FORWARD';
    public const BACK = 'BACK';
    public const BACK14 = 'BACK14';
    public const REPAIR = 'REPAIR';
    public const FORWARD_AND_BACK = 'FORWARD_AND_BACK';
    public const REPLENISHMENT = 'REPLENISHMENT';
    public const TRANSFER = 'TRANSFER';
    public const COST_PER_COURIER_QUOTE = 'COST_PER_COURIER_QUOTE';
    public const COST_PER_COURIER_QUOTE_AND_FORWARD = 'COST_PER_COURIER_QUOTE_AND_FORWARD';
    public const GENERIC = 'GENERIC';
}