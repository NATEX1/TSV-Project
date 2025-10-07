<?php

namespace App\Services;

use MongoDB\Client;

class MongoService
{
    protected $client;
    protected $db;

    public function __construct()
    {
        $this->client = new Client(env('MONGODB_URI', 'mongodb://127.0.0.1:27017'));
        $this->db = $this->client->{env('MONGODB_DATABASE', 'project_arsa')};
    }

    public function collection($name)
    {
        return $this->db->{$name};
    }

    public function findOne($collection, $filter = [], $options = [])
    {
        return $this->collection($collection)->findOne($filter, $options);
    }

    public function find($collection, $filter = [], $options = [])
    {
        return $this->collection($collection)->find($filter, $options);
    }

    public function insertOne($collection, $data)
    {
        return $this->collection($collection)->insertOne($data);
    }

    public function updateOne($collection, $filter, $update, $options = [], $useOperator = false)
    {
        if ($useOperator) {
            return $this->collection($collection)->updateOne($filter, $update, $options);
        } else {
            return $this->collection($collection)->updateOne($filter, ['$set' => $update], $options);
        }
    }


    public function deleteOne($collection, $filter)
    {
        return $this->collection($collection)->deleteOne($filter);
    }

    public function count($collection, $filter = [])
    {
        return $this->collection($collection)->countDocuments($filter);
    }

    public function aggregate($collection, $pipeline = [], $options = [])
    {
        return $this->collection($collection)->aggregate($pipeline, $options);
    }
}
