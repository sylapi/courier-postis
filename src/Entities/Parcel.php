<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Entities;

use Rakit\Validation\Validator;
use Sylapi\Courier\Abstracts\Parcel as ParcelAbstract;

class Parcel extends ParcelAbstract
{
    private string $itemCode;
    private string $description;
    private ?string $description2;
    private string $UOMCode;
    private string $value;
    private string $referenceId;
    private string $type;
    private ?string $content;


    public function setItemCode(string $itemCode): self
    {
        $this->itemCode = $itemCode;

        return $this;
    }

    public function getItemCode(): string
    {
        return $this->itemCode;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription2(?string $description2): self
    {
        $this->description2 = $description2;

        return $this;
    }

    public function getDescription2(): ?string
    {
        return $this->description2;
    }

    public function setUOMCode(string $UOMCode): self
    {
        $this->UOMCode = $UOMCode;

        return $this;
    }

    public function getUOMCode(): string
    {
        return $this->UOMCode;
    }


    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setReferenceId(string $referenceId): self
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }


    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function validate(): bool
    {
        $rules = [
            'weight' => 'required|numeric|min:0.01',
        ];
        $data = [
            'weight' => $this->getWeight(),
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
