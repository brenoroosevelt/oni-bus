<?php
declare(strict_types=1);

namespace OniBus;

interface Chain extends Bus
{
    public function setNext(Bus $bus);
}
