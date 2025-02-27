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

    const API_PATH = '/api/v1/clients/shipments/trace';

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): ResponseStatus
    {
        try {
          
      
            $stream = $this->session->client()->get(self::API_PATH, [
                'query' => [
                    'awblist' => $shipmentId,
                    'type' => 'history',
                ],
            ]);
            $result = json_decode($stream->getBody()->getContents());
            $statusResponse = new StatusResponse((string) new StatusTransformer('TEST'), 'TEST');
            // $statusResponse->setResponse($result);

            //TODO: Implement response transformation

            return $statusResponse;

        } catch (\Exception $e) {
            throw new TransportException($e->getMessage());
        }

        
    }
}
