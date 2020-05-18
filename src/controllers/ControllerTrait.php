<?php

namespace App\controllers;

use Psr\Container\ContainerInterface;

trait ControllerTrait
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    // constructor receives container instance

    /**
     * PageController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}