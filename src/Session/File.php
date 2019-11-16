<?php

namespace Scheduler\Session;

use JSONArray;
use Scheduler\Contracts\Session;

/**
 * File based server session.
 * Session driver.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10quality <info@10quality.com>
 * @package Scheduler
 * @license MIT
 * @version 1.0.3
 */
class File extends JSONArray implements Session
{
    /**
     * Session filename.
     * @since 1.0
     * @var object
     */
    protected static $filename;

    /**
     * Loads session.
     * @since 1.0.0
     *
     * @param string $options Filename as options.
     *
     * @return object Session.
     */
    public static function load($options)
    {
        if (!isset(static::$filename))
            static::$filename = $options;
        $sess = new self;
        if (file_exists($options))
            $sess->read($options);
        return $sess;
    }

    /**
     * Saves session.
     * @since 1.0.0
     */
    public function save()
    {
        $this->write(static::$filename);
    }

    /**
     * Returns flag indicating if key exists in session.
     * @since 1.0.0
     *
     * @return bool     
     */
    public function has($key)
    {
        return array_key_exists($key, $this);
    }

    /**
     * Returns a session value based on a given key.
     * @since 1.0.0
     *
     * @return mixed     
     */
    public function &get($key)
    {
        $default = $this->has($key) ? $this[$key] : null;
        return $default;
    }

    /**
     * Sets a key and a value into session.
     * Server session.
     * @since 1.0.0
     *
     * @param string $key   Key.
     * @param mixed  $value Value;
     */
    public function set($key, $value)
    {
        $this[$key] = $value;
    }
}