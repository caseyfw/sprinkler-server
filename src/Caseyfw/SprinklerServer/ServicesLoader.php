<?php

namespace Caseyfw\SprinklerServer;

use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['sprinkler.service'] = $this->app->share(function () {
            return new Services\SprinklerService($this->app["db"]);
        });
    }
}
