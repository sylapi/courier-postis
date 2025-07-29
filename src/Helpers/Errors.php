<?php

namespace Sylapi\Courier\Postis\Helpers;

use GuzzleHttp\Exception\RequestException;

class Errors
{
    public static function prepareMessage(RequestException $e): string
    {
        $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;
        $headers = $e->getResponse() ? $e->getResponse()->getHeaders() : [];
        $body = $e->getResponse() ? (string) $e->getResponse()->getBody() : null;

        $json = json_decode($body, true);
        
        if (json_last_error() === JSON_ERROR_NONE && isset($json['message'])) {
            $message = $json['message'];
        } else {
            $message = 'An error occurred while processing the request.';
        }

        return $message;
    }
}
