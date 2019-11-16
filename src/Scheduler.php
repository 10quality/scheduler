<?php

namespace Scheduler;

use Scheduler\Session\File;
use Scheduler\Base\Tasker;

/**
 * Jobs scheduler.
 * CRONJOB or server's tasker should call and run this every time,
 * Scheduler will figure it out what to run and at when.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10quality <info@10quality.com>
 * @package Scheduler
 * @license MIT
 * @version 1.0.3
 */
class Scheduler extends Tasker
{
    /**
     * Path to where jobs are located.
     * @since 1.0.0
     * @var array
     */
    protected $jobsPath;

    /**
     * Default constructor.
     * Setup settings and inits server session.
     * @since 1.0.0
     *
     * @param string   $settings      Settings.
     * @param callable $driveCallable Custom drive init callable.
     */
    public function __construct($settings, $driveCallable = null)
    {
        parent::__construct();
        $this->jobsPath = $settings['jobs']['path'];
        switch ($settings['session']['driver']) {
            case 'file':
                $this->session = File::load($settings['session']['path'] . '/scheduler.json');
                break;
            case 'callable':
                if ($driveCallable !== null)
                    $this->session = call_user_func_array($driveCallable, [$settings['session']]);
                break;
        }
    }

    /**
     * Static constructor.
     * @since 1.0.0
     *
     * @param string   $settings      Settings.
     * @param callable $driveCallable Custom drive init callable.
     */
    public static function ready($settings, $driveCallable = null)
    {
        return new self($settings, $driveCallable);
    }
}