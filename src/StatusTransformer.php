<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Abstracts\StatusTransformer as StatusTransformerAbstract;
use Sylapi\Courier\Enums\StatusType;

class StatusTransformer extends StatusTransformerAbstract
{

    /**
     * @var array<string, string>
     */

    public $statuses  = [
        'INITIAL' => StatusType::NEW->value,
        'ready for pickup' => StatusType::PICKUP_READY->value,
        'departed' => StatusType::PROCESSING->value,
        'delivered' => StatusType::DELIVERED->value,
    ];

}
