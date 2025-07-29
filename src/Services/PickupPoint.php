<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;

use Sylapi\Courier\Abstracts\Services\PickupPoint as PickupPointAbstract;

class PickupPoint extends PickupPointAbstract
{
    public function handle(): array
    {
        $payload = $this->getRequest();
        
        if($payload === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        if(!$this->getPickupId()) {
            throw new InvalidArgumentException('PickupId is not defined');
        }

        $payload['recipientLocation']['locationId'] = $this->getPickupId();
        $payload['productCategory'] = "Pickup Point";

        return $payload;
    }
}
