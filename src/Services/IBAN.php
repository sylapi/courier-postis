<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;
use Sylapi\Courier\Abstracts\Service;


class IBAN extends Service
{
    private string $iban;

    public function setIBAN(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getIBAN(): string
    {
        return $this->iban;
    }

    public function handle(): array
    {
        $payload = $this->getRequest();
        
        if($payload === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        $payload['shipmentAdditionalServices']['IBAN'] = $this->getIBAN();

        return $payload;
    }
}
