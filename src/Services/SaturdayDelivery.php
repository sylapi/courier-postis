<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;
use Sylapi\Courier\Abstracts\Service;


class SaturdayDelivery extends Service
{
    public function handle(): array
    {
        $payload = $this->getRequest();
        
        if($payload === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        $payload['shipmentAdditionalServices']['saturdayDelivery'] = true;

        return $payload;
    }
}
