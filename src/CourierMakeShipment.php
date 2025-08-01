<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Postis\Entities\Shipment;
use Sylapi\Courier\Contracts\Shipment as ShipmentContract;
use Sylapi\Courier\Contracts\CourierMakeShipment as CourierMakeShipmentContract;

class CourierMakeShipment implements CourierMakeShipmentContract
{
    public function makeShipment(): ShipmentContract
    {
        return new Shipment();
    }
}
