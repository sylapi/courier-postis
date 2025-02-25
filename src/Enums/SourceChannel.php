<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum SourceChannel: string {
    case ONLINE = 'ONLINE';
    case RETAIL = 'RETAIL';
    case MANUAL = 'MANUAL';
    case BATCH = 'BATCH';
    case MARKETPLACE = 'MARKETPLACE';
}