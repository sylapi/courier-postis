<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Postis\Entities\Receiver;
use Sylapi\Courier\Contracts\Receiver as ReceiverContract;
use Sylapi\Courier\Contracts\CourierMakeReceiver as CourierMakeReceiverContract;


class CourierMakeReceiver implements CourierMakeReceiverContract
{
    public function makeReceiver(): ReceiverContract
    {
        return new Receiver();
    }
}
