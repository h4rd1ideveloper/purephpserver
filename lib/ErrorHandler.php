<?php


namespace Lib;


use DateTimeZone;
use Monolog\Logger;

/**
 * Class ErrorHandler
 * @package Lib
 */
class ErrorHandler extends Logger
{
    /**
     * ErrorHandler constructor.
     * @param string $name
     * @param array $handlers
     * @param array $processors
     * @param DateTimeZone|null $timezone
     */
    public function __construct(string $name, array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null)
    {
        parent::__construct($name, $handlers, $processors, $timezone);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return array
     */
    public function register(string $level, string $message, array $context = []): array
    {
        $this->pushHandler(Factory::StreamHandlerFactory(''));
        return $this->_register($level, $message, $context);
    }

    private function _register(string $level, string $message, array $context)
    {
        return $context;
    }
}