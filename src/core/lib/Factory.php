<?php


namespace Lib;


use App\Abstraction\UserAbstraction;
use App\Database\Bridge\Dao;
use App\model\User;
use Exception;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\StreamHandler;
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
     * @return User
     */
    public static function userFactory(): User
    {
        return new User();
    }

    /**
     * @param array $body
     * @return UserAbstraction
     */
    public static function userAbstractionFactory(array $body): UserAbstraction
    {
        return ((new UserAbstraction($body['username'], $body['password']))
            ->setEmail($body['email'] ?? '')
            ->setPassword($body['password'] ?? '')
            ->setFirstName($body['first_name'] ?? '')
            ->setLastName($body['last_name'] ?? '')
            ->setTel($body['tel'] ?? ''));
    }

    /**
     * @param string $string
     * @return Router
     * @throws Exception
     */
    public static function AppFactory(string $string)
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
     * @param string $reason
     * @return Response|bool
     */
    public static function responseFactory(int $status = 200, array $headers = array(), $body = null, string $version = '1.1', string $reason = null)
    {
        try {
            return new Response($status, $headers, $body, $version, $reason);
        } catch (Exception $e) {
            self::$Error->register(self::$Error::CRITICAL, 'Fail to create Response', [$status, $headers, $body, $version, $reason]);
        }
        return false;
    }

    /**
     * @param string $filename
     * @param int $level
     * @return StreamHandler|bool
     */
    public static function StreamHandlerFactory(string $filename, int $level = 300)
    {
        try {
            return new StreamHandler($filename, $level);
        } catch (Exception $e) {
            self::$Error->register(self::$Error::CRITICAL, $e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine(), [$filename, $level]);
        }
        return false;
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