<?php
use Scheduler\Contracts\Session;

class SessionMock implements Session
{
    protected $data = [];

    public static function load($options)
    {
        return new self(is_array($options) ? $options : []);
    }

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function save()
    {
        // TODO no session to saved
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function &get($key)
    {
        $value = $this->has($key) ? $this->data[$key] : null;
        return $value;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }
}