<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Postis\Entities\Parcel;
use Sylapi\Courier\Contracts\Parcel as ParcelContract;
use Sylapi\Courier\Contracts\CourierMakeParcel as CourierMakeParcelContract;


class CourierMakeParcel implements CourierMakeParcelContract
{
    public function makeParcel(): ParcelContract
    {
        return new Parcel();
    }
}
