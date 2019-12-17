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
    protected $name = null;

    /**
     * ErrorHandler constructor.
     * @param string $name
     * @param array $handlers
     * @param array $processors
     * @param DateTimeZone|null $timezone
     */
    public function __construct(string $name, array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null)
    {
        $this->name = $name;
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

        return $this->_register($level, $message, $context);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return array
     */
    private function _register(string $level, string $message, array $context): array
    {
        switch ($level) {
            case Logger::INFO:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::INFO));
                $this->info($message, $context);
                break;
            }
            case Logger::NOTICE:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::NOTICE));
                $this->notice($message, $context);
                break;
            }
            case Logger::WARNING:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::WARNING));
                $this->warning($message, $context);
                break;
            }
            case Logger::ERROR:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::ERROR));
                $this->error($message, $context);
                break;
            }
            case Logger::CRITICAL:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::CRITICAL));
                $this->critical($message, $context);
                break;
            }
            case Logger::ALERT:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::ALERT));
                $this->alert($message, $context);
                break;
            }
            case Logger::EMERGENCY:
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", Logger::EMERGENCY));
                $this->emergency($message, $context);
                break;
            }
            default :
            {
                $this->pushHandler(Factory::StreamHandlerFactory("ErrorHandler_$this->name.log", 100));
                $this->debug($message, $context);
                break;
            }
        }
        return $context;
    }

}