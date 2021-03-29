<?php
declare(strict_types=1);

namespace OniBus\Exception;

use OniBus\Payload;
use RuntimeException;

class RequiredParameterMissingException extends RuntimeException
{
    /**
     * @var array
     */
    protected $missing = [];

    public function __construct(Payload $payload, array $missing)
    {
        $this->missing = $missing;
        parent::__construct(
            sprintf(
                "Required parameter (%s) is missing for (%s).",
                implode(', ', $missing),
                get_class($payload)
            )
        );
    }

    public function getMissingParameters(): array
    {
        return $this->missing;
    }
}
