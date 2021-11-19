<?php

namespace App\presentation\controllers;

use App\lib\Components;
use Psr\Container\ContainerInterface;

trait ControllerTrait
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;
    protected Components  $viewer;
    // constructor receives container instance

    /**
     * PageController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->viewer = new Components();
    }
}