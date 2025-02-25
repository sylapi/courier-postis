<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;

use Sylapi\Courier\Abstracts\Services\COD as CODAbstract;

class COD extends CODAbstract
{
    private ?string $reference;

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function handle(): array
    {
        $payload = $this->getRequest();
        
        if($payload === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        if(!$this->getAmount()) {
            throw new InvalidArgumentException('Amount is not defined');
        }

        $payload['shipmentAdditionalServices']['cashOnDelivery'] = $this->getAmount();

        if($this->getReference()) {
            $payload['shipmentAdditionalServices']['cashOnDeliveryReference'] = $this->getReference();
        }

        return $payload;
    }
}
