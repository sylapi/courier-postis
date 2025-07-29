<?php

namespace Sylapi\Courier\Postis\Tests\Integration;

use Sylapi\Courier\CourierFactory;
use Sylapi\Courier\Enums\ServiceType;
use Sylapi\Courier\Postis\Enums\UOMCode;
use Sylapi\Courier\Postis\Enums\SendType;
use Sylapi\Courier\Postis\Enums\ParcelType;
use Sylapi\Courier\Postis\Enums\CourierCode;
use Sylapi\Courier\Postis\Enums\PaymentType;
use Sylapi\Courier\Postis\Enums\DeliveryType;
use Sylapi\Courier\Postis\Enums\ShipmentPayer;
use Sylapi\Courier\Postis\Enums\SourceChannel;
use Sylapi\Courier\Postis\Enums\OlzaCourierCode;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;


class IntegrationTest extends PHPUnitTestCase
{
    /**
     * @dataProvider successCreateParcelProvider
     */
    public function testSuccessCreateParcel(string $methodCode, ?string $subMethodCode = null, string $description = 'Test', array $input = []): void
    {

        $login = $_ENV['API_LOGIN'] ?? null;
        $password = $_ENV['API_PASSWORD'] ?? null;
        $clientId = $_ENV['API_CLIENT_ID'] ?? null;

        $this->assertNotEmpty($login);
        $this->assertNotEmpty($password);
        $this->assertNotEmpty($clientId);

        $courier = CourierFactory::create('Postis',[
            'login' => $login,
            'password' => $password,
            'clientId' => $clientId,
            'sandbox' => true,
        ]);

        $senderAddress = $input['sender'] ?? [];

        $sender = $courier->makeSender();
        $sender->setFullName($senderAddress['full_name'] ?? '')
            ->setStreet($senderAddress['street'] ?? '')
            ->setHouseNumber($senderAddress['building_number'] ?? '')
            ->setApartmentNumber($senderAddress['apartment_number'] ?? '')
            ->setCity($senderAddress['city'] ?? '')
            ->setZipCode($senderAddress['zip_code'] ?? '')
            ->setCountry($senderAddress['country'] ?? '')
            ->setCountryCode($senderAddress['country_code'] ?? '')
            ->setContactPerson($senderAddress['full_name'] ?? '')
            ->setEmail($senderAddress['email'] ?? '')
            ->setPhone($senderAddress['phone'] ?? '');

        $address = $input['address'] ?? [];

        $receiver = $courier->makeReceiver();

        $receiver->setFirstName($address['first_name'])
            ->setSurname($address['last_name'])
            ->setStreet($address['street'])
            ->setHouseNumber($address['building_number'])
            ->setApartmentNumber($address['apartment_number'])
            ->setCity($address['city']) 
            ->setZipCode($address['zip_code'])
            ->setCountry($address['country'])
            ->setCountryCode($address['country_code'])
            ->setProvince($address['province'] ?? '')
            ->setContactPerson($address['full_name'])
            ->setEmail($address['email'])
            ->setPhone($address['phone']);

        $this->assertNotEmpty($receiver->getFirstName(), 'Receiver first name should not be empty.');
        $this->assertNotEmpty($receiver->getSurname(), 'Receiver surname should not be empty.');
        $this->assertNotEmpty($receiver->getStreet(), 'Receiver street should not be empty.');
        $this->assertNotEmpty($receiver->getHouseNumber(), 'Receiver house number should not be empty.');
        $this->assertNotEmpty($receiver->getCity(), 'Receiver city should not be empty.');
        $this->assertNotEmpty($receiver->getZipCode(), 'Receiver zip code should not be empty.');
        $this->assertNotEmpty($receiver->getCountry(), 'Receiver country should not be empty.');
        $this->assertNotEmpty($receiver->getCountryCode(), 'Receiver country code should not be empty.');
        $this->assertNotEmpty($receiver->getContactPerson(), 'Receiver contact person should not be empty.');
        $this->assertNotEmpty($receiver->getEmail(), 'Receiver email should not be empty.');
        $this->assertNotEmpty($receiver->getPhone(), 'Receiver phone should not be empty.');


      $parcelId = 'parcel#'.rand(1000000000, 9999999999);
      $parcelReferenceId = 'parcelRef#'.rand(1000000000, 9999999999);

      $parcel = $courier->makeParcel();
      $parcel
          ->setItemCode($parcelId)
          ->setDescription('Parcel description')
          ->setUOMCode(UOMCode::PCS->value)
          ->setType(ParcelType::PACKAGE->value)
          ->setWeight(1.5)
          ->setContent('Test content')
          ->setReferenceId($parcelReferenceId)
          ->setValue(1000)
          ;

        $orderId = 'order#'.rand(1000000000, 9999999999);
        
        $options = $courier->makeOptions();
        $options->set('paymentType', PaymentType::CREDIT_CARD->value);
        $options->set('deliveryType', DeliveryType::STANDARD_DELIVERY->value);
        $options->set('clientOrderDate', date('Y-m-d H:i:s'));
        $options->set('clientOrderId', $orderId);
        $options->set('senderLocationId','101PL');
        $options->set('sendType',SendType::FORWARD->value);
        $options->set('shipmentPayer', ShipmentPayer::SENDER->value);
        $options->set('sourceChannel', SourceChannel::ONLINE->value);

        if($methodCode) {
            $options->set('courierCode', $methodCode);
        }

        if ($subMethodCode) {
            $options->set('thirdPartyServiceCode', $subMethodCode);
        }
        $options->set('shipmentReference', $orderId.'.'.rand(1000000000, 9999999999));
      
        $services = [];

        if(isset($input['options']['services']) && is_array($input['options']['services'])) {
          
            foreach ($input['options']['services'] as $serviceName => $serviceData) {

                $service = $courier->makeService($serviceName);
               
                if($serviceName === ServiceType::COD->value) {
                    $service->setAmount($serviceData['amount'])
                            ->setCurrency($serviceData['currency'])
                            ->setReference($orderId);
                } elseif ($serviceName === ServiceType::PICKUP_POINT->value) {
                    $service->setPickupId($serviceData['pickupId']);
                } elseif ($serviceName === ServiceType::INSURANCE->value) {
                    $service->setAmount($serviceData['amount']);
                }
                 $services[] = $service;
            }
        }


    


        $shipment = $courier->makeShipment();
        $shipment->setSender($sender)
                ->setReceiver($receiver)
                ->setParcel($parcel)
                ->setContent('TEST')
                ->setOptions($options);

        foreach ($services as $service) {
            $shipment->addService($service);
        }

      $response = $courier->createShipment($shipment);


      $this->assertSame($response->getReferenceId(), $orderId, 'Response reference ID should match the order ID.');
      $this->assertNotEmpty($response->getShipmentId(), 'Response shipment ID should not be empty.');
      $this->assertNotEmpty($response->getParcelId(), 'Response parcel ID should not be empty.');
      $this->assertSame($response->getParcelId(), $parcelId, 'Response parcel ID should match the generated parcel ID.');
      $this->assertNotEmpty($response->getParcelReferenceId(), 'Response parcel reference ID should not be empty.');
      $this->assertSame($response->getParcelReferenceId(), $parcelReferenceId, 'Response parcel reference ID should match the generated parcel reference ID.');

      $this->assertTrue(true, "Test for method $methodCode with description $description passed successfully.");
    }

    public static function successCreateParcelProvider(): array
    {

        $sender = [
            'pl' => [
              'type' => 'SENDER',
              'full_name' => 'Nazwa Firmy/Nadawca',
              'first_name' => 'Nazwa Firmy/Nadawca',
              'street' => 'Ulica',
              'building_number' => '2a',
              'apartment_number' => '1',
              'city' => 'Miasto',
              'zip_code' => '66100',
              'country_code' => 'pl',
              'country' => 'Poland',
              'phone' => '+48500600700',
              'email' => 'sender@savicki.pl',
            ],
            'cz' => [
              'type' => 'SENDER',
              'full_name' => 'Název firmy/Odesílatel',
              'first_name' => 'Název firmy/Odesílatel',
              'street' => 'Ulice',
              'building_number' => '2a',
              'apartment_number' => '1',
              'city' => 'Město',
              'zip_code' => '66100',
              'country_code' => 'cz',
              'country' => 'Czechia',
              'phone' => '+420600700800',
              'email' => 'sender@savicki.pl',
            ],
            'sk' => [
              'type' => 'SENDER',
              'full_name' => 'Název firmy/Odesílatel',
              'first_name' => 'Název firmy/Odesílatel',
              'street' => 'Ulice',
              'building_number' => '2a',
              'apartment_number' => '1',
              'city' => 'Město',
              'zip_code' => '66100',
              'country_code' => 'sk',
              'country' => 'Slovakia',
              'phone' => '+420600700800',
              'email' => 'sender@savicki.pl',
            ],            
      ];
      
       

        $options = [
          'inpost_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => 'WAW36APP',
                  ],
                  'Insurance' => [
                      'amount' => 1000,
                      'currency' => 'PLN',
                  ],
              ],
          ],
          'gls_cz_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => 'CZ11000-PARCELLOCK10',
                  ],
              ],
          ],
          'gls_sk_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => 'SK81107-PLOCKER001',
                  ],
              ],
          ],
          'ppl_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => 'KM10736452',
                  ],
              ],
          ],
          'balikovna_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => '18520',
                  ],
              ],
          ],
          'balikobox_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => '291312',
                  ],
              ],
          ],
          'econt_pickup_point' => [
              'services' => [
                  'PickupPoint' => [
                      'pickupId' => '34029',
                  ],
              ],
          ],
          'cod_pl' => [
              'services' => [
                  'COD' => [
                      'amount' => 100,
                      'currency' => 'PLN',
                  ],
              ],
          ],
          'cod_cz' => [
              'services' => [
                  'COD' => [
                      'amount' => 1000,
                      'currency' => 'CZK',
                  ],
              ],
          ],
          'cod_sk' => [
              'services' => [
                  'COD' => [
                      'amount' => 100,
                      'currency' => 'EUR',
                  ],
              ],
          ],
          'cod_hu' => [
              'services' => [
                  'COD' => [
                      'amount' => 10000,
                      'currency' => 'HUF',
                  ],
              ],
          ],
      ];

      $addresses = [
          'pl' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Jan Kowalski',
                  'first_name' => 'Jan',
                  'last_name' => 'Kowalski',
                  'street' => 'osiedle Widok',
                  'building_number' => '22e',
                  'apartment_number' => '8',
                  'city' => 'Świebodzin',
                  'zip_code' => '66-200',
                  'country_code' => 'pl',
                  'country' => 'Poland',
                  'province' => 'Lubusz',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
              'oc_format' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Jan Kowalski',
                  'first_name' => 'Jan',
                  'last_name' => 'Kowalski',
                  'street' => 'osiedle Widok 22e/8',
                  'building_number' => '-',
                  'apartment_number' => '-',
                  'city' => 'Świebodzin',
                  'zip_code' => '66-200',
                  'country_code' => 'pl',
                  'country' => 'Poland',
                  'province' => 'Lubusz',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
              'too_long_street_80' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Jan Kowalski',
                  'first_name' => 'Jan',
                  'last_name' => 'Kowalski',
                  'street' => 'ul. Przyjaciół Zatrzymanych Wspomnień Pod Rozświetlonymi Gwiazdami Nocy Jesiennej',
                  'building_number' => '23b',
                  'apartment_number' => '2',
                  'city' => 'Świebodzin',
                  'zip_code' => '66-200',
                  'country_code' => 'pl',
                  'country' => 'Poland',
                  'province' => 'Lubusz',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],

          ],
          'ro' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Ion Popescu',
                  'first_name' => 'Ion',
                  'last_name' => 'Popescu',
                  'street' => 'Strada Libertății',
                  'building_number' => '10',
                  'apartment_number' => '5',
                  'city' => 'București',
                  'zip_code' => '010123',
                  'province_code' => 'B',
                  'country_code' => 'ro',
                  'country' => 'Romania',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],

          'bg' => [

              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Ivan Ivanov',
                  'first_name' => 'Ivan',
                  'last_name' => 'Ivanov',
                  'street' => 'bulevard Vasil Levski',
                  'building_number' => '15',
                  'apartment_number' => '3',
                  'city' => 'Sofia',
                  'zip_code' => '1000',
                  'country_code' => 'bg',
                  'country' => 'Bulgaria',
                  'province' => '',   
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],

              'cyrillic' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Иван Иванов',
                  'first_name' => 'Иван',
                  'last_name' => 'Иванов',
                  'street' => 'булевард Васил Левски',
                  'building_number' => '15',
                  'apartment_number' => '3',
                  'city' => 'София',
                  'zip_code' => '1000',
                  'country' => 'Bulgaria',
                  'province' => '',
                  'country_code' => 'bg',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],

          'cz' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Petr Novák',
                  'first_name' => 'Petr',
                  'last_name' => 'Novák',
                  'street' => 'Nádražní',
                  'building_number' => '1234',
                  'apartment_number' => '56',
                  'city' => 'Praha',
                  'zip_code' => '110 00',
                  'province' => '',
                  'country' => 'Czechia',
                  'country_code' => 'cz',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
              'local_phone_number' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Petr Novák',
                  'first_name' => 'Petr',
                  'last_name' => 'Novák',
                  'street' => 'Nádražní',
                  'building_number' => '1234',
                  'apartment_number' => '56',
                  'city' => 'Praha',
                  'zip_code' => '110 00',
                  'country_code' => 'cz',
                  'country' => 'Czechia',
                  'phone' => '+420602123456',
                  'email' => 'test@savicki.pl',
              ],
          ],

          'sk' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Ján Novák',
                  'first_name' => 'Ján',
                  'last_name' => 'Novák',
                  'street' => 'Hlavná',
                  'building_number' => '45',
                  'apartment_number' => '12',
                  'city' => 'Bratislava',
                  'zip_code' => '811 01',
                  'country_code' => 'sk',
                  'country' => 'Slovakia',
                  'phone' => '+421910123456',
                  'email' => 'test@savicki.pl',
              ],
              'local_phone_number' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Ján Novák',
                  'first_name' => 'Ján',
                  'last_name' => 'Novák',
                  'street' => 'Hlavná',
                  'building_number' => '45',
                  'apartment_number' => '12',
                  'city' => 'Bratislava',
                  'zip_code' => '811 01',
                  'country_code' => 'sk',
                  'country' => 'Slovakia',
                  'phone' => '+421910123456',
                  'email' => 'test@savicki.pl',
              ],
          ],

          'de' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Jan Kowalski',
                  'first_name' => 'Jan',
                  'last_name' => 'Kowalski',
                  'street' => 'Richard-Strauss-Straße',
                  'building_number' => '13',
                  'apartment_number' => '',
                  'city' => 'Berlin',
                  'zip_code' => '14193',
                  'country_code' => 'DE',
                  'country' => 'Germany',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
              'oc_format' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Jan Kowalski',
                  'first_name' => 'Jan',
                  'last_name' => 'Kowalski',
                  'street' => 'Richard-Strauss-Straße 13',
                  'building_number' => '-',
                  'apartment_number' => '-',
                  'city' => 'Berlin',
                  'zip_code' => '14193',
                  'country_code' => 'DE',
                  'country' => 'Germany',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],

          'gr' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Giannis Papadopoulos',
                  'first_name' => 'Giannis',
                  'last_name' => 'Papadopoulos',
                  'street' => 'Odos Ermou',
                  'building_number' => '50',
                  'apartment_number' => '7',
                  'city' => 'Athina',
                  'zip_code' => '105 63',
                  'country_code' => 'gr',
                  'country' => 'Greece',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
              'cyrillic' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Γιάννης Παπαδόπουλος',
                  'first_name' => 'Γιάννης',
                  'last_name' => 'Παπαδόπουλος',
                  'street' => 'Οδός Ερμού',
                  'building_number' => '50',
                  'apartment_number' => '7',
                  'city' => 'Αθήνα',
                  'zip_code' => '105 63',
                  'country_code' => 'gr',
                  'country' => 'Greece',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],
          'ua' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Ivan Petrov',
                  'first_name' => 'Ivan',
                  'last_name' => 'Petrov',
                  'street' => 'vulytsia Khreshchatyk',
                  'building_number' => '22',
                  'apartment_number' => '11',
                  'city' => 'Kyiv',
                  'zip_code' => '01001',
                  'country_code' => 'ua',
                  'country' => 'Ukraine',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
              'cyrillic' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Іван Петров',
                  'first_name' => 'Іван',
                  'last_name' => 'Петров',
                  'street' => 'вулиця Хрещатик',
                  'building_number' => '22',
                  'apartment_number' => '11',
                  'city' => 'Київ',
                  'zip_code' => '01001',
                  'country_code' => 'ua',
                  'country' => 'Ukraine',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],
          'hr' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'Ivan Horvat',
                  'first_name' => 'Ivan',
                  'last_name' => 'Horvat',
                  'street' => 'Ulica kralja Tomislava',
                  'building_number' => '14',
                  'apartment_number' => '4',
                  'city' => 'Zagreb',
                  'zip_code' => '10000',
                  'country_code' => 'hr',
                  'country' => 'Croatia',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],
          'hu' => [
              'correct' => [
                  'type' => 'SHIPPING',
                  'full_name' => 'János Nagy',
                  'first_name' => 'János',
                  'last_name' => 'Nagy',
                  'street' => 'Andrássy út',
                  'building_number' => '60',
                  'apartment_number' => '10',
                  'city' => 'Budapest',
                  'zip_code' => '1061',
                  'country_code' => 'hu',
                  'country' => 'Hungary',
                  'phone' => '+48504289693',
                  'email' => 'test@savicki.pl',
              ],
          ],
      ];


        return [
            [CourierCode::GLSPL->value, null, 'correct', ['address' => $addresses['pl']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::GLSPL->value, null, 'oc_format', ['address' => $addresses['pl']['oc_format'], 'sender' => $sender['pl']]], 
            [CourierCode::GLSPL->value, null, 'too_long_street_80', ['address' => $addresses['pl']['too_long_street_80'], 'sender' => $sender['pl']]], 
            [CourierCode::GLSPL->value, null, 'correct COD', ['address' => $addresses['pl']['too_long_street_80'], 'sender' => $sender['pl'], 'options' => $options['cod_pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['pl']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'oc_format', ['address' => $addresses['pl']['oc_format'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['cz']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['ro']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['hu']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['gr']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['hr']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['ua']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'cyrillic', ['address' => $addresses['ua']['cyrillic'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'correct', ['address' => $addresses['bg']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::UPS->value, null, 'cyrillic', ['address' => $addresses['bg']['cyrillic'], 'sender' => $sender['pl']]], 
            [CourierCode::DHLPL->value, null, 'correct', ['address' => $addresses['pl']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::DHL->value, null, 'correct', ['address' => $addresses['de']['correct'], 'sender' => $sender['pl']]], 
            [CourierCode::INPOST->value, null, 'correct', ['address' => $addresses['pl']['correct'], 'sender' => $sender['pl'],  'options' => $options['inpost_pickup_point']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::GLS->value, 'local_phone_number', ['address' => $addresses['cz']['local_phone_number'], 'sender' => $sender['cz']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::GLS_PS->value, 'local_phone_number', ['address' => $addresses['cz']['local_phone_number'], 'sender' => $sender['cz'], 'options' => $options['gls_cz_pickup_point']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::GLS->value, 'local_phone_number', ['address' => $addresses['sk']['local_phone_number'], 'sender' => $sender['sk']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::GLS_PS->value, 'local_phone_number', ['address' => $addresses['sk']['local_phone_number'], 'sender' => $sender['sk'], 'options' => $options['gls_sk_pickup_point']]],  
            [CourierCode::OLZA->value, OlzaCourierCode::GLS->value, 'correct', ['address' => $addresses['hu']['correct'], 'sender' => $sender['cz']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::CP->value, 'correct', ['address' => $addresses['cz']['correct'], 'sender' => $sender['cz']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::ZAS_ECONT_HD->value, 'correct', ['address' => $addresses['bg']['correct'], 'sender' => $sender['cz']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::ZAS_ECONT_HD->value, 'cyrillic', ['address' => $addresses['bg']['cyrillic'], 'sender' => $sender['cz']]], 
            [CourierCode::OLZA->value, OlzaCourierCode::ZAS_ECONT_PP->value, 'correct', ['address' => $addresses['bg']['correct'], 'sender' => $sender['cz'], 'options' => $options['econt_pickup_point']]],  
            [CourierCode::OLZA->value, OlzaCourierCode::ZAS_ECONT_PP->value, 'cyrillic', ['address' => $addresses['bg']['cyrillic'], 'sender' => $sender['cz'], 'options' => $options['econt_pickup_point']]],  
            [CourierCode::OLZA->value, OlzaCourierCode::BMCG_FAN->value, 'correct', ['address' => $addresses['ro']['correct'], 'sender' => $sender['cz']]],  
            [CourierCode::OLZA->value, OlzaCourierCode::PPL_PAR->value, 'correct', ['address' => $addresses['cz']['correct'], 'sender' => $sender['cz']]],  
            [CourierCode::OLZA->value, OlzaCourierCode::PPL_PS->value, 'correct', ['address' => $addresses['cz']['correct'], 'sender' => $sender['cz'], 'options' => $options['ppl_pickup_point']]],  
            [CourierCode::OLZA->value, OlzaCourierCode::CP_BAL->value, 'correct PP', ['address' => $addresses['cz']['correct'], 'sender' => $sender['cz'], 'options' => $options['balikovna_pickup_point']]],
    ];
  }
}