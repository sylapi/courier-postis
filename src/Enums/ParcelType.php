<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum ParcelType: string {
    case ENVELOPE = 'ENVELOPE';
    case PACKAGE = 'PACKAGE';
}