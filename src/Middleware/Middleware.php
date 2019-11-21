<?php


namespace App\middleware;


use Closure;
use Psr\Http\Message\HttpHelper;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;

/**
 * Class Middleware
 * @package App
 */
class Middleware extends HttpHelper
{
    /**
     * @var array
     */
    private static $closures = [];

    /**
     * @param Closure $closure
     * @param string $key
     */
    public static function addClosure(Closure $closure, string $key = 'global'): void
    {
        self::$closures[$key][] = $closure;
    }

    /**
     * @param string $route
     * @param Request $request
     * @param Closure $finalClosure
     * @return Response|string
     */
    public static function executeMiddleware(string $route, Request $request, Closure $finalClosure)
    {
        $closures = array_merge((self::getClosures())['global'] ?? [], (self::getClosures())[$route] ?? []);
        if (count($closures)) {
            for ($i = 0; $i < count($closures); $i++) {
                if (isset($closures[$i + 1])) {
                    $request = $closures[$i]($request, $closures[$i + 1]);
                } else {
                    return $closures[$i]($request, $finalClosure);
                }
            }
        }
        return $finalClosure($request);
    }

    /**
     * @return array
     */
    public static function getClosures(): array
    {
        return self::$closures;
    }

    /**
     * @param array $closures
     */
    public static function setClosures(array $closures): void
    {
        self::$closures = $closures;
    }

    /**
     * @return Closure
     */
    final public static function bodyJSON(): Closure
    {
        return static function (Request $request, Closure $closure) {
            return $closure($request->withBody(self::stream_for(self::toJson(self::getBodyByMethod($request)))));
        };
    }
}