<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use GuzzleHttp\Client;
use Sylapi\Courier\Postis\Entities\Credentials;

class Session
{
    private $credentials = null;
    private $login = null;
    private $password = null;
    private $token = null;
    private $client = null;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
        $this->login = $this->credentials->getLogin();
        $this->password = $this->credentials->getPassword();
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    public function token(): string
    {
        if(!$this->token){
            $this->auth();
        }

        return $this->token;
    }

    public function client()
    {
        if (!$this->client) {
            $this->client = $this->initializeSession();
        }

        return $this->client;
    }

    private function initializeSession(): Client
    {
        $this->client = new Client([
            'base_uri' => $this->credentials->getApiUrl(),
            'headers'  => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer '.$this->token(),
            ],
        ]);

        return $this->client;
    }


    private function auth()
    {
        $client = new Client([
            'base_uri' => $this->credentials->getApiUrl(),
            'headers'  => [
                'Content-Type'  => 'application/json',
            ],
        ]);
        

        $stream = $client->post('/unauthenticated/login', [
            'json' => [
                'name'    => $this->login,
                'password' => $this->password,
            ],
        ]);

        $result = json_decode($stream->getBody()->getContents());

        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Json data is incorrect');
        }

        if (isset($result->token)) {
            $this->token = $result->token;
        } else {
            throw new \Exception('Token not found');
        }
    }

}
