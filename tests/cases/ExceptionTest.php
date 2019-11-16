<?php

use Scheduler\Scheduler;

class ExceptionTest extends Scheduler_TestCase
{
    /**
     * Test run.
     * @since 1.0.4
     */
    public function testContinuesRun()
    {
        // Prepare
        $scheduler = $this->getScheduler()
            // Jobs
            ->job('ExceptionJob', function($task) {
                return $task->daily();
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEmpty($res);
    }
    /**
     * Test run.
     * @since 1.0.4
     */
    public function testJobExceptionCallable()
    {
        // Prepare
        $scheduler = $this->getScheduler()
            // Jobs
            ->job('ExceptionJob', function($task) {
                return $task->daily()
                    ->onException(function($e) {
                        echo $e->getMessage();
                    });
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEquals('Test error.', $res);
    }
    /**
     * Test run.
     * @since 1.0.4
     */
    public function testJobReset()
    {
        // Prepare
        $session = new SessionMock();
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return $session;
                                }
                            ],
            ])
            // Jobs
            ->job('ExceptionJob', function($task) {
                return $task->daily()
                    ->canReset();
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEquals(0, $session->get('jobs')->ExceptionJob->time);
    }
    /**
     * Test run.
     * @since 1.0.4
     */
    public function testJobNonReset()
    {
        // Prepare
        $session = new SessionMock([
            'last_exec_time'    => strtotime('-1 hour'),
            'jobs'              => (object)[
                                    'ExceptionJob'   => (object)[
                                        'time'  => strtotime('-1 hour'),
                                    ],
                                ],
        ]);
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return $session;
                                }
                            ],
            ])
            // Jobs
            ->job('ExceptionJob', function($task) {
                return $task->daily();
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertNotEquals(0, $session->get('jobs')->ExceptionJob->time);
    }
    /**
     * Test run.
     * @since 1.0.4
     */
    public function testJobNonLog()
    {
        // Prepare
        $session = new SessionMock();
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return $session;
                                }
                            ],
            ])
            // Jobs
            ->job('ExceptionJob', function($task) {
                return $task->daily();
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEmpty($session->get('jobs'));
    }
    /**
     * Test run.
     * @since 1.0.4
     */
    public function testGlobalExceptionCallable()
    {
        // Prepare
        $scheduler = Scheduler::ready([
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => [
                                'driver'    => 'callable',
                                'callable'  => function() use(&$session) {
                                    return new SessionMock();
                                }
                            ],
                'events'    => [
                                'on_exception' => function($e) {
                                    echo $e->getMessage();
                                }
                            ],
            ])
            // Jobs
            ->job('ExceptionJob', function($task) {
                return $task->daily();
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEquals('Test error.', $res);
    }
}