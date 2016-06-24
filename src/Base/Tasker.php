<?php

namespace Scheduler\Base;

use stdClass;
use Closure;
use Exception;
use Scheduler\Task;

/**
 * Scheduler base abstract class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10quality <info@10quality.com>
 * @package Scheduler
 * @license MIT
 * @version 1.0.0
 */
abstract class Tasker
{
    /**
     * Path to where jobs are located.
     * @since 1.0.0
     * @var array
     */
    protected $jobsPath;

    /**
     * Path to where jobs are located.
     * @since 1.0.0
     * @var array
     */
    protected $session;

    /**
     * List of jobs to run.
     * @since 1.0.0
     * @var array
     */
    protected $jobs;

    /**
     * Default constructor.
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->time = time();
        $this->jobs = [];
    }

    /**
     * Adds job to list.
     * @since 1.0.0
     *
     * @param string $name Job name.
     * @param object $task Clouse task creator.
     *
     * @return this for chaining
     */
    public function job($name, Closure $task)
    {
        $filename = $this->jobsPath . '/' . $name . '.php';

        // Validate that file exists
        if (!file_exists($filename))
            throw new Exception('Job file not located at:' . $filename);

        // Include job class
        include $filename;
        $job = new $name();

        // Assign task
        $job->task = $task(new Task);

        // Add to list of jobs
        $this->jobs[] = $job;

        // Chaining
        return $this;    
    }

    /**
     * Starts tasker.
     * @since 1.0.0
     *
     * @return this for chaining
     */
    public function start()
    {
        // Check on first time execution
        if (!$this->session->has('last_exec_time'))
            $this->session->set('last_exec_time', 0);
        // Loop jobs
        for ($i = count($this->jobs) - 1; $i >= 0; --$i) {
            $this->executeJob($i);
        }
        // Scheduler finished.
        $this->session->set('last_exec_time', time());
        $this->session->save();
        // Chaining
        return $this;    
    }

    /**
     * Executes job at index passed by.
     * @since 1.0.0
     *
     * @param int $index Index.
     */
    private function executeJob($index)
    {
        try {
            if ($this->onSchedule($this->jobs[$index])) {
                $this->jobs[$index]->execute();
                $this->log($this->jobs[$index]);
            }
        } catch (Exception $e) {
            // TODO
        }
    }

    /**
     * Returns flag indicating if task is onSchedule and ready to be executed.
     * @since 1.0.0
     *
     * @param object $job.
     */
    private function onSchedule(Job &$job)
    {
        if (!$this->session->has('jobs')
            && !isset($this->session->get('jobs')->{get_class($job)})
        ) return true;

        switch ($job->task->interval) {
            case Task::MIN1:
                if ($this->lapsedTimeToMinutes($job) > 1)
                    return true;
                break;
            case Task::MIN5:
                if ($this->lapsedTimeToMinutes($job) > 5)
                    return true;
                break;
            case Task::MIN10:
                if ($this->lapsedTimeToMinutes($job) > 10)
                    return true;
                break;
            case Task::MIN30:
                if ($this->lapsedTimeToMinutes($job) > 30)
                    return true;
                break;
            case Task::MIN60:
                if ($this->lapsedTimeToMinutes($job) > 60)
                    return true;
                break;
            case Task::MIN720:
                if ($this->lapsedTimeToMinutes($job) > 720)
                    return true;
                break;
            case Task::DAILY:
                if ($this->timeToDay($job) != date('Ymd'))
                    return true;
                break;
            case Task::MONTHLY:
                if ($this->timeToMonth($job) != date('Ym'))
                    return true;
                break;
            case Task::WEEKLY:
                if ($this->timeToWeek($job) != date('YW'))
                    return true;
                break;
            case Task::NOW:
                    return true;
        }
        return false;
    }

    /**
     * Logs executed job.
     * @since 1.0.0
     *
     * @param object $job.
     */
    private function log(Job &$job)
    {
        if (!$this->session->has('jobs'))
            $this->session->set('jobs', new stdClass);

        if (!isset($this->session->get('jobs')->{get_class($job)}))
            $this->session->get('jobs')->{get_class($job)} = new stdClass;

        $this->session->get('jobs')->{get_class($job)}->time = time();
    }

    /**
     * Returns lapsed time to minutes.
     * @since 1.0.0
     *
     * @return float
     */
    private function lapsedTimeToMinutes(Job &$job)
    {
        return ($this->time - $this->session->get('jobs')->{get_class($job)}->time) / 60;
    }

    /**
     * Returns last executed to day.
     * @since 1.0.0
     *
     * @return string
     */
    private function timeToDay(Job &$job)
    {
        return date('Ymd', $this->session->get('jobs')->{get_class($job)}->time);
    }

    /**
     * Returns last executed to day.
     * @since 1.0.0
     *
     * @return string
     */
    private function timeToMonth(Job &$job)
    {
        return date('Ym', $this->session->get('jobs')->{get_class($job)}->time);
    }

    /**
     * Returns last executed to day.
     * @since 1.0.0
     *
     * @return string
     */
    private function timeToWeek(Job &$job)
    {
        return date('YW', $this->session->get('jobs')->{get_class($job)}->time);
    }
}