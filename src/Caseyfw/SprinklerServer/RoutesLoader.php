<?php

namespace Caseyfw\SprinklerServer;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();

    }

    private function instantiateControllers()
    {
        $this->app['sprinkler.controller'] = $this->app->share(function () {
            return new Controllers\SprinklerController($this->app['sprinkler.service']);
        });
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        $api->get('/sprinkler', "sprinkler.controller:getAll");
        $api->post('/sprinkler', "sprinkler.controller:save");
        $api->put('/sprinkler/{id}', "sprinkler.controller:update");
        $api->delete('/sprinkler/{id}', "sprinkler.controller:delete");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
