<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Abstracts\StatusTransformer as StatusTransformerAbstract;
use Sylapi\Courier\Enums\StatusType;

class StatusTransformer extends StatusTransformerAbstract
{

    /**
     * @var array<string, mixed>
     */

    public $statuses  = [
        'INITIAL' => StatusType::NEW,
        'ready for pickup' => StatusType::PICKUP_READY,
        'departed' => StatusType::PROCESSING,
        'delivered' => StatusType::DELIVERED,
    ];

}
