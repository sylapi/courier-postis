<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Contracts\CourierGetLabels as CourierGetLabelsContract;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Postis\Responses\Label as LabelResponse;
use Sylapi\Courier\Contracts\LabelType as LabelTypeContract;
use Sylapi\Courier\Responses\Label as ResponseLabel;

class CourierGetLabels implements CourierGetLabelsContract
{
    private $session;

    const API_PATH = '/api/v1/clients/shipments/{shipmentId}/label';


    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId, LabelTypeContract $labelType): ResponseLabel
    {
        try {
            $stream = $this->session
            ->client()
            ->get($this->getPath($shipmentId));

            $labelResponse = new LabelResponse((string) $stream->getBody()->getContents());

            return $labelResponse;

        } catch (\Exception $e) {
            throw new TransportException($e->getMessage());
        }
    }

    private function getPath(string $shipmentId): string
    {
        return str_replace('{shipmentId}', $shipmentId, self::API_PATH);
    }
}
