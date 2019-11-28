<?php


namespace Lib;


use App\Abstraction\UserAbstraction;
use App\Database\Bridge\Dao;
use App\model\User;
use Exception;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\Response;
use Server\Router;

/**
 * Class Factory
 * @package Lib
 */
class Factory extends Helpers
{
    /**
     * @var ErrorHandler|null
     */
    private static $Error = null;

    /**
     * Factory constructor.
     * @param ErrorHandler $errorHandler
     */
    public function __construct(ErrorHandler $errorHandler)
    {
        self::$Error = $errorHandler;
    }

    public static function aliasFakerFactory()
    {
        return \Faker\Factory::create('pt_BR');
    }

    /**
     * @param array $credentials
     * @return User
     * @throws Exception
     */
    public static function userFactory(): User
    {
        return new User();
    }

    /**
     * @param string $string
     * @return Router
     * @throws Exception
     */
    public static function AppFactory(string $string): Router
    {
        try {
            return new Router($string);
        } catch (Exception $e) {
            self::$Error->register(self::$Error::CRITICAL, $e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine(), [$string]);
            exit(
            self::responseFactory(500, ['Content-Type' => 'application/json'], ['error' => true, 'message' => $e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine()])
            );
        }
    }

    /**
     * @param int $status
     * @param array $headers
     * @param null $body
     * @param string $version
     * @param null $reason
     * @return Response
     * @throws Exception
     */
    public static function responseFactory($status = 200, $headers = array(), $body = null, $version = '1.1', $reason = null): Response
    {
        try {
            return new Response($status, $headers, $body, $version, $reason);
        } catch (Exception $e) {
            self::$Error->register(self::$Error::CRITICAL, 'Fail to create Response', [$status, $headers, $body, $version, $reason]);
        }
    }

    /**
     * @param string $filename
     * @param int $level
     * @return StreamHandler
     */
    public static function StreamHandlerFactory(string $filename, int $level = 300): StreamHandler
    {
        try {
            return new StreamHandler($filename, $level);
        } catch (Exception $e) {
            self::$Error->register(self::$Error::CRITICAL, $e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine(), [$filename, $level]);
        }
    }

    /**
     * @param array|string $login
     * @param string $pass
     * @return UserAbstraction
     */
    public static function UserAbstractionFactory($login, string $pass = ''): UserAbstraction
    {
        if (is_string($login)) {
            return new UserAbstraction($login, $pass ?? '');
        }
        return isset($login['login'], $login['pass']) ? new UserAbstraction($login['login'], $login['pass']) : new UserAbstraction($login[0], $login[1]);
    }

    /**
     * @param string $label
     * @return ErrorHandler
     */
    public static function errorFactory(string $label)
    {
        return new ErrorHandler($label);
    }

    /**
     * User constructor.
     * @param array|string $credentials
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $name
     * @param string $type
     * @param string $db_path
     * @return Dao
     * @throws Exception
     */
    public function DaoFactory($credentials, string $host = 'localhost', string $user = 'root', string $pass = '', string $name = '', string $type = 'mysql', string $db_path = ''): Dao
    {
        return self::isArrayAndHasKeys($credentials, 3) ? new Dao(
            $credentials['host'],
            $credentials['user'],
            $credentials['pass'],
            $credentials['name'],
            $credentials['type'] ?? $type,
            $credentials['db_path'] ?? $db_path
        ) : new Dao($host, $user, $pass, $name, $type, $db_path);
    }
}