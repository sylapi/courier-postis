<?php

namespace Sylapi\Courier\Postis\Services;

use InvalidArgumentException;

use Sylapi\Courier\Abstracts\Service;

class Example extends Service
{
    public function handle(): array
    {
        $consign = $this->getRequest();
        
        if($consign === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        $consign['services']['example'] = true;
        

        return $consign;
    }
}
