<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use BadMethodCallException;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Responses\Parcel as ResponseParcel;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;

class CourierPostShipment implements CourierPostShipmentContract
{
    public function __construct(private Session $session) {}

    public function postShipment(Booking $booking): ResponseParcel
    {
      $session = $this->session;
      $parcel = new ResponseParcel();
      throw new BadMethodCallException('Not implemented');
    }
}
