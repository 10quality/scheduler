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

### Events

You can define callable event handlers when configuring the scheduler, like in the following example:

```php
Scheduler::ready([
    'jobs'      => ['path' => __DIR__.'/jobs'],
    'session'   => ['driver' => 'file', 'path'=>__DIR__.'/.tmp'],
    'events'    => [
                    'on_start' => function($microtime) {
                        // Do something during event
                    },
                    'on_job_start' => function($jobName, $microtime) {
                        if ($jobName === 'Job')
                            // Do something during event
                    }
                ],
]);
```

List of events (parameters can be used optionally):

| Event key | Parameters | Description |
| --- | --- | --- |
| `on_init` |  | Triggered before session is validated. |
| `on_start` | *int* **$microtime** | Triggered before executing any job. |
| `on_finish` | *int* **$microtime** | Triggered after executing all jobs and saving session. |
| `on_job_start` | *string* **$jobName**, *int* **$microtime** | Triggered before executing of a job. |
| `on_job_finish` | *string* **$jobName**, *int* **$microtime** | Triggered after executing of job. |

### Handling Exceptions

Scheduler will run continuously without interruption. You can handle exceptions individually per job or globally to log them as needed.

#### Global Exception Handling

Add a `on_exception` callable handler in the scheduler's events configuration, like in the following example:

```php
Scheduler::ready([
    'jobs'      => ['path' => __DIR__.'/jobs'],
    'session'   => ['driver' => 'file', 'path'=>__DIR__.'/.tmp'],
    'events'    => [
                    'on_exception' => function($e) {
                        // Do anything with exception
                        echo $e->getMessage();
                    }
                ],
]);
```

#### Job Exceptions

Add a exception handling callable when defining the job task, like this:

```php
Scheduler::ready([...])
    ->job('MyJob', function($task) {
        // Here we define the task interval
        return $task->daily()
            ->onException(function($e) {
                // Do anything with exception
                echo $e->getMessage();
            });
    });
```

To reset the job when an exception occurs and force it to be executed on the next run, indicate it on the task:
```php
Scheduler::ready([...])
    ->job('MyJob', function($task) {
        // Here we define the task interval
        return $task->daily()
            ->canReset()
            ->onException(function(Exception $e) {
                // Do anything with exception
            });
    });
```

### Session

#### File

Indicate the folder where session file will be stored. Make sure this folder has the proper permissions.

```php
Scheduler::ready([
    'jobs'      => ['path' => __DIR__.'/jobs'],
    'session'   => ['driver' => 'file', 'path'=>__DIR__.'/.tmp'],
]);
```

#### Callable

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
Scheduler::ready([
    'jobs'      => ['path' => __DIR__.'/jobs'],
    'session'   => [
                    'driver'    => 'callable',,
                    'callable'  => function() use(&$session) {
                        return MySessionDriver::load( $custom_options );
                    }
                ],
]);
```

## Requirements

* PHP >= 5.4

## Coding guidelines

PSR-4.

## LICENSE

The MIT License (MIT)

Copyright (c) 2016-2019 [10Quality](http://www.10quality.com).