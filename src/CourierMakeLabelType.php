<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Postis\Entities\LabelType;
use Sylapi\Courier\Contracts\LabelType as LabelTypeContract;
use Sylapi\Courier\Contracts\CourierMakeLabelType as CourierMakeLabelTypeContract;

class CourierMakeLabelType implements CourierMakeLabelTypeContract
{
    public function makeLabelType(): LabelTypeContract
    {
        return new LabelType();
    }
}
