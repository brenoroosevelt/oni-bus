<?php
declare(strict_types=1);

namespace OniBus\Exception;

use InvalidArgumentException;

class RequiredParameterMissingException extends InvalidArgumentException
{
    /**
     * @var array
     */
    protected $missing = [];

    /**
     * @param object $subject
     * @param array $missing
     */
    public function __construct($subject, array $missing)
    {
        $this->missing = $missing;
        parent::__construct(
            sprintf(
                "Required parameter (%s) is missing for (%s).",
                implode(', ', $missing),
                is_object($subject) ? get_class($subject) : gettype($subject)
            )
        );
    }

    public function getMissingParameters(): array
    {
        return $this->missing;
    }
}
