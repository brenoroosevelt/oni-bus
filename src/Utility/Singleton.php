<?php
declare(strict_types=1);

namespace OniBus\Utility;

use RuntimeException;

trait Singleton
{
    protected static $instance = null;

    /**
     * @return $this
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            self::$instance = static::singleInstance();
        }

        return self::$instance;
    }

    /**
     * @return $this
     */
    abstract protected static function singleInstance();

    public static function __callStatic($name, $arguments)
    {
        $instance = self::instance();
        if (!method_exists($instance, $name)) {
            throw new RuntimeException(
                sprintf("Call to undefined method (%s::%s).", get_class($instance), $name)
            );
        }

        return call_user_func_array([$instance, $name], $arguments);
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }
}
