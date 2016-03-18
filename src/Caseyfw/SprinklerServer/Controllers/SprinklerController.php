<?php

namespace Caseyfw\SprinklerServer\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SprinklerController
{
    public function index()
    {
        return new Response("Home.");
    }
}
