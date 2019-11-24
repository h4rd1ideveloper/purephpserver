<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\controller\AppController;
use App\middleware\Middleware;
use Lib\Factory;
use Psr\Http\Message\Request;

try {
    /**
     * $app = Class Router
     * @author Yan Santos Policar <policarpo@ice.ufjf.br>
     * @version 1.1.0
     * @see Closure
     * @see die
     * @see Request {@RequestInterface}
     * @see Response {@ResponseInterface}
     * @method get(string $string, Closure $param, ?Closure|array $closure = false)
     * @method post(string $string, Closure $param, ?Closure|array $closure = false)
     * @method patch(string $string, Closure $param, ?Closure|array $closure = false)
     * @method put(string $string, Closure $param, ?Closure|array $closure = false)
     * @method delete(string $string, Closure $param, ?Closure|array $closure = false)
     * @method middleware(Closure|array $param)
     */
    $app = Factory::AppFactory('.env');
    $app->get('/', (new AppController)::allAboutTheRequest(), Middleware::authenticate());
    $app->get('/dashboard', (new AppController)::dashboard());
    $app->get('/login', (new AppController)::login());
    $app->run();
} catch (Exception $e) {
    $app->runException($e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine());
    exit;
}
