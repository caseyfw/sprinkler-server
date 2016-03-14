<?php

namespace Caseyfw\SprinklerServer\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SprinklerController
{
    protected $sprinklersService;

    public function __construct($service)
    {
        $this->sprinklersService = $service;
    }

    public function getAll()
    {
        return new JsonResponse($this->sprinklersService->getAll());
    }

    public function save(Request $request)
    {

        $sprinkler = $this->getDataFromRequest($request);
        return new JsonResponse(array("id" => $this->sprinklersService->save($sprinkler)));

    }

    public function update($id, Request $request)
    {
        $sprinkler = $this->getDataFromRequest($request);
        $this->sprinklersService->update($id, $sprinkler);
        return new JsonResponse($sprinkler);

    }

    public function delete($id)
    {

        return new JsonResponse($this->sprinklersService->delete($id));

    }

    public function getDataFromRequest(Request $request)
    {
        return $sprinkler = array(
            "sprinkler" => $request->request->get("sprinkler")
        );
    }
}