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
        'ORIGINAL_DELIVERED' => StatusType::DELIVERED->value,
    ];

   



}
