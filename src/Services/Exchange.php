<?php

namespace Sylapi\Courier\Postis\Services;

use Sylapi\Courier\Abstracts\Service;

class Exchange extends Service
{
    private ?string $description = null;

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function handle(): array
    {
        $payload = $this->getRequest();

        $payload['sendType'] = 'FORWARD_AND_BACK';
        $payload['returnServices'] = [['type' => 'box']];

        if ($this->getDescription()) {
            $payload['returnServices'][0]['description'] = $this->getDescription();    
        }
        
        return $payload;
    }
}
