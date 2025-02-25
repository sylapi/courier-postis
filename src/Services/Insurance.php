<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;

use Sylapi\Courier\Abstracts\Services\Insurance as InsuranceAbstract;

class Insurance extends InsuranceAbstract
{
    public function handle(): array
    {
        $payload = $this->getRequest();
        
        if($payload === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        $payload['shipmentAdditionalServices']['insurance'] = true;

        return $payload;
    }
}
