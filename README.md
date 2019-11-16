# Scheduler

[![Latest Stable Version](https://poser.pugx.org/10quality/scheduler/v/stable)](https://packagist.org/packages/10quality/scheduler)
[![Total Downloads](https://poser.pugx.org/10quality/scheduler/downloads)](https://packagist.org/packages/10quality/scheduler)
[![License](https://poser.pugx.org/10quality/scheduler/license)](https://packagist.org/packages/10quality/scheduler)

PHP job scheduler. (Cronjob tasker)

## Installing

Via composer:

```bash
composer require 10quality/scheduler
```

## Usage

### Scheduler

Create a php file that will be called by cronjob, [Sample](https://github.com/10quality/scheduler/blob/v1.0/tests/_environment/scheduler.php), and include the following code lines:

```php
// 1) LOAD COMPOSER AUTOLOAD OR BOOTSTRAP FIRST

// 2)
use Scheduler\Scheduler;
```

Init scheduler with the required configuration:
```php
Scheduler::ready([
    'jobs'      => ['path' => __DIR__.'/jobs'],
    'session'   => ['driver' => 'file', 'path'=>__DIR__.'/.tmp'],
]);
```

*NOTE:* Scheduler requires a defined jobs path (where jobs are located) and the session driver/settings to use to handle intervals (the package only supports file atm).

Register your jobs and execution interval.
```php
Scheduler::ready([...])
    ->job('MyJob', function($task) {
        return $task->daily();
    });
```

Start scheduler:
```php
Scheduler::ready([...])
    ->job(...)
    ->start();
```

Then setup a cronjob task to run scheduler, as follows:
```bash
* * * * * php /path/to/scheduler-file.php >> /dev/null 2>&1
```

### Jobs

Scheduler runs jobs written in PHP. A job is a PHP class extended from `Scheduler\Base\Job` class. Once a job is registered in the scheduler, it will call to the `execute()` function in runtime, [Sample](https://github.com/10quality/scheduler/blob/v1.0/tests/_environment/jobs/TestJob.php).

Create a job mimicking the following example:

```php
use Scheduler\Base\Job;

class MyJob extends Job
{
    public function execute()
    {
        // My code here...
    }
}
```

For the example above, the job file must be named `MyJob.php` and must be stored in the `jobs path`.

### Intervals

Execution intervals are defined when registering a job:
```php
Scheduler::ready([...])
    ->job('MyJob', function($task) {
        // Here we define the task interval
        return $task->daily();
    });
```

Available intervals:
```php
// On every execution
$task->now();

// Daily
$task->daily();

// Weekly
$task->weekly();

// Monthly
$task->monthly();

// Every minute
$task->everyMinute();

// Every 5 minutes
$task->everyFiveMinutes();

// Every 10 minutes
$task->everyTenMinutes();

// Every 30 minutes
$task->everyHalfHour();

// Every hour
$task->everyHour();

// Every 12 hours
$task->everyTwelveHours();

// Every 2 days
$task->everyTwoDays();

// Every 3 days
$task->everyThreeDays();

// Every XXX minutes (custom minutes)
// @param init $minutes Custome minutes interval
$task->custom($minutes);
```

### Session

You can create your own session driver by extending from the [Session](https://github.com/10quality/scheduler/blob/v1.0/src/Contracts/Session.php) interface:

```php

use Scheduler\Contracts\Session;

class MySessionDriver implements Session
{
    /*
     * See and develop required interface methods....
     */
}
```

Then, when initializing the scheduler, set the driver to `callable` and add the *callable* as second parameter, like the following example:

```php
Scheduler::ready(
    [
        'jobs'      => ['path' => __DIR__.'/jobs'],
        'session'   => ['driver' => 'callable'],
    ],
    function() {
        return MySessionDriver::load( $custom_options );
    }
);
```

## Requirements

* PHP >= 5.4

## Coding guidelines

PSR-4.

## LICENSE

The MIT License (MIT)

Copyright (c) 2016-2019 [10Quality](http://www.10quality.com).