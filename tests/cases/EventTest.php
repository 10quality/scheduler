<?php

use Scheduler\Scheduler;

class EventTest extends Scheduler_TestCase
{
    /**
     * Test event.
     * @since 1.0.4
     */
    public function testEventOnStart()
    {
        // Prepare
        $time = null;
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return new SessionMock();
                                }
                            ],
                'events'    => [
                                'on_start' => function($microtime) use(&$time) {
                                    $time = $microtime;
                                }
                            ],
            ])
            // Jobs
            ->job('DummyJob', function($task) {
                return $task->now();
            });
        // Execute
        $scheduler->start();
        // Assert
        $this->assertNotNull($time);
        $this->assertInternalType('string', $time);
    }
    /**
     * Test event.
     * @since 1.0.4
     */
    public function testEventOnFinish()
    {
        // Prepare
        $start = null;
        $finish = null;
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return new SessionMock();
                                }
                            ],
                'events'    => [
                                'on_start'  => function($microtime) use(&$start) {
                                    $start = $microtime;
                                },
                                'on_finish' => function($microtime) use(&$finish) {
                                    $finish = $microtime;
                                },
                            ],
            ])
            // Jobs
            ->job('DummyJob', function($task) {
                return $task->now();
            });
        // Execute
        $scheduler->start();
        // Assert
        $this->assertNotNull($finish);
        $this->assertInternalType('string', $finish);
        $this->assertNotEquals($start, $finish);
    }
    /**
     * Test event.
     * @since 1.0.4
     */
    public function testEventOnJobStart()
    {
        // Prepare
        $start = null;
        $name = null;
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return new SessionMock();
                                }
                            ],
                'events'    => [
                                'on_job_start'  => function($job, $microtime) use(&$name, &$start) {
                                    $name = $job;
                                    $start = $microtime;
                                },
                            ],
            ])
            // Jobs
            ->job('DummyJob', function($task) {
                return $task->now();
            });
        // Execute
        $scheduler->start();
        // Assert
        $this->assertNotNull($name);
        $this->assertNotNull($start);
        $this->assertInternalType('string', $start);
        $this->assertEquals('DummyJob', $name);
    }
    /**
     * Test event.
     * @since 1.0.4
     */
    public function testEventOnJobFinish()
    {
        // Prepare
        $start = null;
        $finish = null;
        $name = null;
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return new SessionMock();
                                }
                            ],
                'events'    => [
                                'on_job_start'  => function($job, $microtime) use(&$start) {
                                    $start = $microtime;
                                },
                                'on_job_finish'  => function($job, $microtime) use(&$name, &$finish) {
                                    $name = $job;
                                    $finish = $microtime;
                                },
                            ],
            ])
            // Jobs
            ->job('DummyJob', function($task) {
                return $task->now();
            });
        // Execute
        $scheduler->start();
        // Assert
        $this->assertNotNull($name);
        $this->assertNotNull($finish);
        $this->assertInternalType('string', $finish);
        $this->assertNotEquals($start, $finish);
        $this->assertEquals('DummyJob', $name);
    }
    /**
     * Test event.
     * @since 1.0.4
     */
    public function testEventInit()
    {
        // Prepare
        $init = false;
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return new SessionMock();
                                }
                            ],
                'events'    => [
                                'on_init' => function() use(&$init) {
                                    $init = true;
                                }
                            ],
            ])
            // Jobs
            ->job('DummyJob', function($task) {
                return $task->now();
            });
        // Execute
        $scheduler->start();
        // Assert
        $this->assertTrue($init);
    }
}