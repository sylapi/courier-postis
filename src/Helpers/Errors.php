<?php

namespace Sylapi\Courier\Postis\Helpers;

class Errors
{
    public static function prepareMessage(string $response): string
    {
        preg_match('/"message"\s*:\s*"([^"]+)"/', $response, $messageMatch);
        $message = $messageMatch[1] ?? $response;

        preg_match('/"status"\s*:\s*(\d+)/', $response, $statusMatch);
        $status = $statusMatch[1] ?? '';

        return 'Error ' . $status . ': ' . $message;
    }
}
