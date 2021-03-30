<?php
declare(strict_types=1);

namespace OniBus\Utility;

trait Singleton
{
    protected static $instance = null;

    public static function instance()
    {
        if (is_null(static::$instance)) {
            self::$instance = static::singleInstance();
        }

        return self::$instance;
    }

    abstract protected static function singleInstance();

    protected function __construct()
    {
    }
}
