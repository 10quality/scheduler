<?php

use Scheduler\Scheduler;

class SchedulerTest extends Scheduler_TestCase
{
    /**
     * Test command execution.
     * @since 1.0.0
     */
    public function testCommandExec()
    {
        // PREPARE
        $return = exec('php ' . ENV_PATH . '/scheduler.php');
        // ASSERT
        $this->assertEquals($return, 'EXECUTED!');
    }
    /**
     * Test.
     * @since 1.0.3
     */
    public function testSessionMock()
    {
        // Prepare
        $scheduler = $this->getScheduler()
            // Jobs
            ->job('TestJob', function($task) {
                return $task->now();
            });
        // Execute
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEquals($res, 'EXECUTED!');
    }
    /**
     * Test session instance exception.
     * @since 1.0.3
     * 
     * @expectedException        Exception
     * @expectedExceptionMessage Session driver must implement "Scheduler\Contracts\Session" interface.
     */
    public function testSessionException()
    {
        // Prepare
        $scheduler = Scheduler::ready(
            [
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => ['driver' => 'callable'],
            ],
            function() {
                return new stdClass;
            }
        );
        // Execute
        $scheduler->start();
    }
}