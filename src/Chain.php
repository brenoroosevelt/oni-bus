<?php
declare(strict_types=1);

namespace XBus;

interface Chain extends Bus
{
    public function setNext(Bus $bus);
}
