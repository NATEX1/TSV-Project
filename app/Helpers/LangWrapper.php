<?php

namespace App\Helpers;

class LangWrapper
{
    protected $data;
    protected $lang;

    public function __construct($data)
    {
        $this->data = (array) $data;
        $this->lang = session('lang', 'th');
    }

    public function __get($name)
    {
        $key = $name . '_' . $this->lang;
        return $this->data[$key] ?? $this->data[$name . '_th'] ?? null;
    }
}
