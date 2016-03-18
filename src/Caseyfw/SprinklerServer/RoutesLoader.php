<?php

namespace Caseyfw\SprinklerServer;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->app['sprinkler.controller'] = $this->app->share(function () {
            return new Controllers\SprinklerController();
        });
        $this->app['sprinkler.rest-controller'] = $this->app->share(function () {
            return new Controllers\SprinklerRESTController($this->app['sprinkler.service']);
        });
    }

    public function bindRoutes()
    {
        // Frontend controller.
        $frontend = $this->app['controllers_factory'];

        $frontend->get('/', 'sprinkler.controller:index');

        $this->app->mount('', $frontend);

        // REST Controller.
        $api = $this->app['controllers_factory'];

        $api->get('/sprinklers', 'sprinkler.rest-controller:getAll');
        $api->get('/sprinkler/{id}', 'sprinkler.rest-controller:get');
        $api->get('/sprinkler/{id}/instruction', 'sprinkler.rest-controller:getInstruction');
        $api->post('/sprinkler', 'sprinkler.rest-controller:save');
        $api->put('/sprinkler/{id}', 'sprinkler.rest-controller:update');
        $api->delete('/sprinkler/{id}', 'sprinkler.rest-controller:delete');

        $this->app->mount($this->app['api.endpoint'].'/'.$this->app['api.version'], $api);
    }
}
