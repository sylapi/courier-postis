<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Entities;

use Sylapi\Courier\Abstracts\Credentials as CredentialsAbstract;

class Credentials extends CredentialsAbstract
{
    public function setCustomOption(string $customOption): self
    {
        $this->set('customOption', $customOption);

        return $this;
    }

    public function getCustomOption(): string
    {
        return $this->get('customOption');
    }

}
