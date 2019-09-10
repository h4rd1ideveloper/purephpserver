<?php


namespace App\routes;

use App\http\Request;
use App\http\Response;
use Closure;

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
                        return 'empty';
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
     * @param $index
     * @return Closure
     */
    public function getClosures($index)
    {
        return $this->closures[$index];
    }
}