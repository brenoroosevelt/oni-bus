<?php
declare(strict_types=1);

namespace OniBus\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Handler
{
    /**
     * @var string|null
     */
    protected $message;

    public function __construct(string $message = null)
    {
        $this->message = $message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
