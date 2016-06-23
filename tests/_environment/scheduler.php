<?php
require_once __DIR__.'/../../vendor/autoload.php';

use Scheduler\Scheduler;

Scheduler::ready([
        'jobs'      => ['path' => __DIR__.'/jobs'],
        'session'   => ['driver' => 'file', 'path'=>__DIR__.'/.tmp'],
    ])
    // TestJob
    ->job('TestJob', function($task) {
        return $task->now();
    })
    // Start scheduler.
    ->start();