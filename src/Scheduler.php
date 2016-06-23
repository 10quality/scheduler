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
 * @version 1.0.0
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
     * @param string $settings Settings.
     */
    public function __construct($settings)
    {
        parent::__construct();
        $this->jobsPath = $settings['jobs']['path'];
        switch ($settings['session']['driver']) {
            case 'file':
                $this->session = File::load($settings['session']['path'] . '/scheduler.json');
                break;
        }
    }

    /**
     * Static constructor.
     * @since 1.0.0
     *
     * @param string $settings Settings.
     */
    public static function ready($settings)
    {
        return new self($settings);
    }
}