<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Entities;

use Sylapi\Courier\Abstracts\LabelType as LabelTypeAbstract;

class LabelType extends LabelTypeAbstract
{
    public function validate(): bool
    {
        return true;
    }
}
