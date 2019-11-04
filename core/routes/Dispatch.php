<?php

namespace Server;

use App\controller\AppController;
use Closure;
use Psr\Http\Message\Request;
use Psr\Http\Message\Response;

/**
 * Class Dispatch
 * @author Yan Santos Policar <policarpo@ice.ufjf.br>
 * @version 1.1.0
 * @package App\routes
 */
class Dispatch
{
    /**
     * @var array
     */
    private $closures = array();
    private $middles = array();

    /**
     * Dispatch constructor.
     * @param array $closures
     */
    public function __construct($closures = array())
    {
        if (count($closures)) {
            self::setClosures($closures);
        } else {
            self::setClosures(
                array(
                    'index' => function (Request $req, Response $res) {
                        return AppController::allAboutTheRequest($req);
                    }
                )
            );
        }
    }

    /**
     * @param array $closures
     */
    private function setClosures($closures)
    {
        $this->closures = $closures;
    }

    /**
     * @param string $key
     * @return Closure
     */
    public function getMiddleware(string $key): Closure
    {
        return $this->middles[$key];
    }

    /**
     * @param Closure $middleware
     * @param string $key
     * @return Dispatch
     */
    public function setMiddleware(Closure $middleware, string $key): self
    {
        $this->middles[$key] = $middleware;
        return $this;
    }

    /**
     * @param $index
     * @return Closure
     */
    public function getClosures($index)
    {
        return $this->closures[$index];
    }
}