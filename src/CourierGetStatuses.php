<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Postis\Enums\TestId;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Responses\Status as ResponseStatus;
use Sylapi\Courier\Postis\Responses\Status as StatusResponse;
use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;

class CourierGetStatuses implements CourierGetStatusesContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): ResponseStatus
    {


        try {
            $payload = [
                'shipmentId' => $shipmentId,
                'token' => $this->session->token(),
            ];

            $result = [
                'response' => 'SUCCESS',
                'shipmentId' => $shipmentId,
                'status' => 'ORIGINAL_DELIVERED',
            ];
      

            $statusResponse = new StatusResponse((string) new StatusTransformer($result['status']), $result['status']);
            $statusResponse->setResponse($result);
            $statusResponse->setRequest($payload);
            return $statusResponse;

        } catch (\Exception $e) {
            throw new TransportException($e->getMessage());
        }

        
    }
}
