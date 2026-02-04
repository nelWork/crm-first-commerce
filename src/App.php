<?php

namespace App;

use App\Database\Database;
use App\Mailer\Mailer;
use App\Router\Router;
use App\Http\Request;
use App\Container\Container;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container;
    }

    public function run(): void
    {
        $this->container->router->route(
            $this->container->request->uri(),
            $this->container->request->method()
        );

    }
}
