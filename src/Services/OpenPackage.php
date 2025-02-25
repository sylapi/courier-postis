<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;
use Sylapi\Courier\Abstracts\Service;


class OpenPackage extends Service
{
    public function handle(): array
    {
        $payload = $this->getRequest();
        
        if($payload === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        $payload['shipmentAdditionalServices']['openPackage'] = true;

        return $payload;
    }
}
