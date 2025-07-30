<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum CourierCode: string {
    case GLSPL = 'GLSPL';
    case GLS = 'GLS';
    case OLZA = 'OLZA';
    case EUROCOMM = 'EUROCOMM';
    case UPS = 'UPS';
    case DHLPL = 'DHLPL';
    case ECONT = 'ECONT';
    case DHL = 'DHL';
    case INPOST = 'INPOST';
}