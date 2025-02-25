<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use BadMethodCallException;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Responses\Parcel as ResponseParcel;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;

class CourierPostShipment implements CourierPostShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseParcel
    {
      throw new BadMethodCallException('Not implemented');
    }
}
