<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Postis\Entities\Credentials;

class SessionFactory
{
    private $sessions = [];

    const API_URL = 'https://shipments.postisgate.com/api/v1';

    public function session(Credentials $credentials): Session
    {
        $apiUrl = self::API_URL;

        $credentials->setApiUrl($apiUrl);

        $key = sha1( $apiUrl.':'.$credentials->getLogin().':'.$credentials->getPassword());

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new Session($credentials));
    }
}
