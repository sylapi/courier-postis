<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

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

            if($stream->getStatusCode() !== 200) {
                throw new TransportException('Invalid response from API');
            }

            $result = json_decode($stream->getBody()->getContents());

            $statuses = $result[0]->eventsList ?? [];

            if (empty($statuses)) {
                throw new TransportException('No statuses found for the given shipment ID');
            }

            $status = $statuses [array_key_last($statuses)];

            $statusResponse = new StatusResponse((string) new StatusTransformer($status->courierStatusDescription ?? $status->clientStatusDescription), $status->courierStatusDescription ?? $status->clientStatusDescription);
            $statusResponse->setResponse($result);

            return $statusResponse;

        } catch (\Exception $e) {
            throw new TransportException($e->getMessage());
        }

        
    }
}
