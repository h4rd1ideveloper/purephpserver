<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use App\Abstraction\Token\Token;
use App\controller\AppController;
use Lib\Factory;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;

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
    $authenticate = function (Request $request) {
        if (isset($_SESSION['user']) && $user = $_SESSION['user'] && $env = HttpHelper::getEnvFrom('.env')) {
            if (isset($env, $env['SECRET']) && Token::isValidByKey("$user", $env['SECRET'])) {
                AppController::redirect("token?$user");
            }
        }
        return $request;
    };

    $app->post('/token', AppController::token());
    $app->get('/dashboard', AppController::dashboard(), $authenticate);
    $app->get('/login', AppController::login());
    $app->get('/', function (Request $req) {
        return new Response(200, HttpHelper::JSON_H, HttpHelper::getBodyByMethod($req));
    });
    $app->run();
} catch (Exception $e) {
    $app->runException($e->getMessage() . $e->getTraceAsString() . $e->getCode() . $e->getLine());
    exit;
}
