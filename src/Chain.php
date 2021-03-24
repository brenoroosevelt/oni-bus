<?php
declare(strict_types=1);

namespace XBus;

interface Chain
{
    public function setNext(Bus $bus);
}
