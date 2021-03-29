<?php
declare(strict_types=1);

namespace OniBus\Utility;

trait Singleton
{
    private static $instance = null;

    /**
     * @return $this
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            self::$instance = static::createInstance();
        }

        return self::$instance;
    }

    /**
     * @return $this
     */
    abstract protected static function createInstance();

    protected function __construct()
    {
    }

    private function __clone()
    {
    }
}
