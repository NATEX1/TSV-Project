<?php

namespace App\Http\Controllers;

use App\Services\MongoService;

abstract class Controller
{
    protected $mongo;

    public function __construct(MongoService $mongo)
    {
        $this->mongo = $mongo;
    }
}
