<?php

namespace Sylapi\Courier\Postis\Responses;
use Sylapi\Courier\Postis\Entities\Booking;
use Sylapi\Courier\Responses\Shipment as ShipmentResponse;

class Shipment extends ShipmentResponse
{

    private ?string $referenceId = null;
    private ?string $parcelId = null;
    private ?string $parcelReferenceId = null;



    public function setReferenceId(?string $referenceId) : self
    {
        $this->referenceId = $referenceId;
        return $this;
    }

    public function getReferenceId() : ?string
    {
        return $this->referenceId;
    }

    public function setParcelId(?string $parcelId) : self
    {
        $this->parcelId = $parcelId;
        return $this;
    }

    public function getParcelId() : ?string
    {
        return $this->parcelId;
    }

    public function setParcelReferenceId(?string $parcelReferenceId) : self
    {
        $this->parcelReferenceId = $parcelReferenceId;
        return $this;
    }


    public function getParcelReferenceId() : ?string
    {
        return $this->parcelReferenceId;
    }

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
