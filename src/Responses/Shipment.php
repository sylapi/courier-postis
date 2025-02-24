<?php

namespace Sylapi\Courier\Postis\Responses;
use Sylapi\Courier\Postis\Entities\Booking;
use Sylapi\Courier\Responses\Shipment as ShipmentResponse;

class Shipment extends ShipmentResponse
{
    public function getBooking() : ?Booking
    {

        if(!$this->getResponse()) {
            return null;
        }

        $booking = new Booking();
        $booking->setShipmentId($this->getShipmentId());

        return $booking;

    }
}
