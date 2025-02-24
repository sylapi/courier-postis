<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Entities;

use Rakit\Validation\Validator;
use Sylapi\Courier\Abstracts\Shipment as ShipmentAbstract;
use Sylapi\Courier\Contracts\Shipment as ShipmentContract;

class Shipment extends ShipmentAbstract
{
    private $customOption;

    public function getCustomOption(): ?string
    {
        return $this->customOption;
    }

    public function setCustomOption(string $customOption): ShipmentContract
    {
        $this->customOption = $customOption;

        return $this;
    }

    public function getQuantity(): int
    {
        return 1;
    }

    public function validate(): bool
    {
        $rules = [
            'quantity' => 'required|min:1|max:1',
            'parcel'   => 'required',
            'sender'   => 'required',
            'receiver' => 'required',
        ];

        $data = [
            'quantity' => $this->getQuantity(),
            'parcel'   => $this->getParcel(),
            'sender'   => $this->getSender(),
            'receiver' => $this->getReceiver(),
        ];

        $validator = new Validator();

        $validation = $validator->validate($data, $rules);
        if ($validation->fails()) {
            $this->setErrors($validation->errors()->toArray());

            return false;
        }

        return true;
    }
}
