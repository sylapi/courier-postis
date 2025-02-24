<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Entities;

use InvalidArgumentException;
use Sylapi\Courier\Abstracts\Service as ServiceAbstract;

class Service extends ServiceAbstract
{
    public function handle(): array
    {
        $consign = $this->getRequest();
        
        if($consign === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        $services = $this->all();  //TODO: add services

        return $consign;
    }
    public function validate(): bool
    {
        return true;
    }
}
