<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Postis\Helpers\Errors;
use Sylapi\Courier\Postis\Entities\Parcel;
use Sylapi\Courier\Postis\Entities\Options;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Exceptions\TransportException;

use Sylapi\Courier\Responses\Shipment as ResponseShipment;
use Sylapi\Courier\Postis\Entities\Shipment as ShipmentEntity;
use Sylapi\Courier\Postis\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment as CourierCreateShipmentContract;

class CourierCreateShipment implements CourierCreateShipmentContract
{

    const API_PATH = '/api/v1/clients/shipments';

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

            $stream = $this->session
            ->client()
            ->post(
                self::API_PATH,
                [
                    'json' => $payload,
                ]
            );

            $result = json_decode($stream->getBody()->getContents());


            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Json data is incorrect');
            }

            $response->setRequest($payload);
            $response->setResponse($result);
            $response->setShipmentId($result->shipmentId);
            $response->setReferenceId($result->clientOrderId);
            $response->setParcelId($result?->shipmentParcels[0]?->itemCode);
            $response->setParcelReferenceId($result?->shipmentParcels[0]?->parcelReferenceId);
            

            return $response;

        } catch (RequestException $e) {
            throw new TransportException(Errors::prepareMessage($e));
        }
        catch (\Exception $e) {
            throw new TransportException('An error occurred while creating the shipment: ' . $e->getMessage());
        }
    }

    private function getPayload(ShipmentEntity $shipment): array
    {
        /**
         * @var Options $options
         */
        $options = $shipment->getOptions();


        /**
         * @var Parcel $parcel
         */
        $parcel = $shipment->getParcel();


        $payload = [
            'clientOrderDate' => $options->get('clientOrderDate' ,date('Y-m-d H:i:s')),
            'clientOrderId' => $options->get('clientOrderId'),
            'paymentType' => $options->get('paymentType'),
            'paymentType' => 'CASH',
            'productCategory' => $options->get('deliveryType'),
            'recipientLocation' => [          
                'addressText' => $shipment->getReceiver()->getAddress(),
                'contactPerson' => $shipment->getReceiver()->getFullName(),
                'country' => $shipment->getReceiver()->getCountry(),
                'county' => $shipment->getReceiver()->getProvince(),
                'locality' => $shipment->getReceiver()->getCity(),
                'localityId' => '',
                'name' => $shipment->getReceiver()->getFullName(),
                'phoneNumber' => $shipment->getReceiver()->getPhone(),
                'postalCode' => $shipment->getReceiver()->getZipCode(),
                'streetName' => $shipment->getReceiver()->getStreet(),
                'buildingNumber' => $shipment->getReceiver()->getHouseNumber(). ' '.$shipment->getReceiver()->getApartmentNumber(),
                'email' => $shipment->getReceiver()->getEmail(),
            ],
            'senderLocationId' => $options->get('senderLocationId'),
            'senderLocation' => [
                "locationId" => $options->get('senderLocationId'),
                "name" => $shipment->getSender()->getFullName(),
                "contactPerson" => $shipment->getSender()->getFullName(),
                "phoneNumber" => $shipment->getSender()->getPhone(),
                "email" => $shipment->getSender()->getEmail(),
                "country" => $shipment->getSender()->getCountryCode(),
                "county" => $shipment->getSender()->getProvince(),
                "locality" => $shipment->getSender()->getCity(),
                "streetName" => $shipment->getSender()->getStreet(),
                "buildingNumber" => $shipment->getSender()->getHouseNumber(). ' '.$shipment->getSender()->getApartmentNumber(),
                "postalCode" => $shipment->getSender()->getZipCode(),
                "addressText" =>  $shipment->getSender()->getAddress(),
            ],
            "packingList" => $parcel->getReferenceId(),
            'sendType' => $options->get('sendType'),
            'shipmentParcels' => [
                [
                    'itemCode' =>   $parcel->getItemCode(),
                    'itemDescription1' => $parcel->getDescription(),
                    'itemUOMCode' => $parcel->getUOMCode(),
                    'parcelType' => $parcel->getType(),
                    'parcelBrutWeight' => $parcel->getWeight(),
                    'parcelContent' => $parcel->getContent(),
                    'parcelReferenceId' => $parcel->getReferenceId(),
                    'parcelDeclaredValue' => $parcel->getValue(),
                    'parcelHeight' => $parcel->getHeight(),
                    'parcelWidth' => $parcel->getWidth(),
                    'parcelLength' => $parcel->getLength(),
                ],
            ],
            'shipmentPayer' => $options->get('shipmentPayer'),
            'shipmentReference' => $options->get('shipmentReference'),
            'sourceChannel' => $options->get('sourceChannel'),
            'courierId' => $options->get('courierCode'),
        ];

        if($options->has('thirdPartyServiceCode')) {
            $payload['shipmentAdditionalValues']['thirdPartyServiceCode'] = $options->get('thirdPartyServiceCode');
        } 


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
