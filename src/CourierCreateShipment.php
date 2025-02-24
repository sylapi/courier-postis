<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Postis\Enums\TestId;
use Sylapi\Courier\Postis\Entities\Options;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Responses\Shipment as ResponseShipment;
use Sylapi\Courier\Postis\Entities\Shipment as ShipmentEntity;
use Sylapi\Courier\Postis\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment as CourierCreateShipmentContract;

class CourierCreateShipment implements CourierCreateShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseShipment
    {
        $response = new ShipmentResponse();
        
        /**
         * @var ShipmentEntity $shipment
         */
        if (!$shipment->validate()) {
            throw new ValidateException('Invalid Shipment');
        }

        try{
            
            $payload = $this->getPayload($shipment);
            $response->setRequest($payload);
            $result = ['response' => 'SUCCESS', 'shipmentId' => $shipment->getCustomOption()];
            $response->setResponse($result);
            $response->setShipmentId($result['shipmentId']);
            

            return $response;

        } catch (\Exception $e) {
            throw new TransportException($e->getMessage());
        }
    }

    private function getPayload(ShipmentEntity $shipment): array
    {
        /**
         * @var Options $options
         */
        $options = $shipment->getOptions();

        $payload = [
            'token' => $this->session->token(),
            'first_name'     => $shipment->getReceiver()->getFirstName(),
            'surname'     => $shipment->getReceiver()->getSurname(),
            'country_code'   => $shipment->getReceiver()->getCountryCode(),
            'zip_code'   => $shipment->getReceiver()->getZipCode(),
            'city'      => $shipment->getReceiver()->getCity(),
            'street'    => $shipment->getReceiver()->getStreet(),
            'phone'     => $shipment->getReceiver()->getPhone(),
            'email'   => $shipment->getReceiver()->getEmail(),
            'content' => $shipment->getContent(),
            'custom_option'      => $shipment->getCustomOption(),
            'sender'   => [
                'full_name'   => $shipment->getSender()->getFullName(),
                'country' => $shipment->getSender()->getCountryCode(),
                'zip_code' => $shipment->getSender()->getZipCode(),
                'city'    => $shipment->getSender()->getCity(),
                'street'  => $shipment->getSender()->getStreet(),
            ],
            'parcels' => [
                [
                    'reference' => $shipment->getContent(),
                    'weight'    => $shipment->getParcel()->getWeight(),
                ],
            ],
        ];

        $services = $shipment->getServices();
        
        if($services) {
            foreach($services as $service) {
                $service->setRequest($payload);
                $payload = $service->handle();
            }
        } 


        return $payload;
    }
}
