<?php


namespace XBus\Handler;

use XBus\Message;

interface ClassMethodMapper
{
    /**
     * @param  Message $message
     * @return ClassMethod
     */
    public function map(Message $message): ClassMethod;
}
