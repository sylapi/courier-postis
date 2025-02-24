<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use stdClass;
use Sylapi\Courier\Postis\Entities\Credentials;

class Session
{
    private $credentials = null;
    private $token = null;
    private $client = null;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    public function token(): string
    {
        $this->token = sha1(date('Y-m-d H:i:s'));

        return $this->token;
    }

    public function client(): stdClass
    {
        if (!$this->client) {
            $this->client = $this->initializeSession();
        }

        return $this->client;
    }

    private function initializeSession(): stdClass
    {
        $this->client = new stdClass();

        return $this->client;
    }
}
