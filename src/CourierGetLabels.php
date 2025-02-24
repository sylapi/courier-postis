<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use PHPUnit\Event\Code\Test;
use Sylapi\Courier\Contracts\CourierGetLabels as CourierGetLabelsContract;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Postis\Responses\Label as LabelResponse;
use Sylapi\Courier\Contracts\LabelType as LabelTypeContract;
use Sylapi\Courier\Postis\Enums\TestId;
use Sylapi\Courier\Responses\Label as ResponseLabel;

class CourierGetLabels implements CourierGetLabelsContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId, LabelTypeContract $labelType): ResponseLabel
    {
        try {
            $payload = [
                'token' => $this->session->token(),
                'shipmentId' => $shipmentId,
            ];

            $result = [
                'label' => 'label',
            ];
            
            $labelResponse = new LabelResponse((string) $result['label']);
            $labelResponse->setResponse($result);
            $labelResponse->setRequest($payload);

            return $labelResponse;

        } catch (\Exception $e) {
            throw new TransportException($e->getMessage());
        }
    }
}
