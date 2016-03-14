<?php

namespace Caseyfw\SprinklerServer\Services;

class BaseService
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}