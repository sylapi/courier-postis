<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis;

use Sylapi\Courier\Abstracts\StatusTransformer as StatusTransformerAbstract;
use Sylapi\Courier\Enums\StatusType;

/**
 * Postis jest agregatorem – sam normalizuje statusy wszystkich kurierow do
 * wlasnego zbioru "client status" (pole clientStatusDescription / defaultClientId
 * w eventsList). Dlatego mapujemy WLASNIE ten znormalizowany status Postis,
 * a nie surowy courierStatusId/courierStatusDescription (ten jest rozny dla
 * kazdego kuriera i bywa null).
 *
 * Klucz = clientStatusDescription (dokladnie jak zwraca API, case-sensitive).
 * Obok kazdego wpisu podany jest potwierdzony defaultClientId.
 *
 * Statusy nieznane (jeszcze nie zaobserwowane) trafiaja do StatusType::PROCESSING
 * przez __toString(), zeby ujednolicony system nigdy nie dostal surowego tekstu.
 * Gdy zaobserwujesz nowy clientStatusDescription – dopisz go tutaj.
 */
class StatusTransformer extends StatusTransformerAbstract
{
    private string $clientStatus;

    /**
     * @var array<string, string>
     */
    public $statuses = [
        'INITIAL'           => StatusType::NEW->value,             // defaultClientId 0
        'order created'     => StatusType::ORDERED->value,         // defaultClientId 1
        'courier warehouse' => StatusType::WAREHOUSE_ENTRY->value, // defaultClientId 3
        'delivered'         => StatusType::DELIVERED->value,       // defaultClientId 5
        'ready for pickup'  => StatusType::PICKUP_READY->value,
        'departed'          => StatusType::PROCESSING->value,
    ];

    public function __construct(string $status)
    {
        parent::__construct($status);
        $this->clientStatus = $status;
    }

    public function __toString(): string
    {
        return $this->statuses[$this->clientStatus] ?? StatusType::PROCESSING->value;
    }
}
