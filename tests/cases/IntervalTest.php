<?php

class IntervalTest extends Scheduler_TestCase
{
    /**
     * Test interval.
     * @since 1.0.3
     */
    public function testNoExecution()
    {
        // Prepare
        $scheduler = $this->getScheduler([
                'last_exec_time'    => strtotime('-1 hour'),
                'jobs'              => (object)[
                                        'TestJob'   => (object)[
                                            'time'  => strtotime('-1 hour'),
                                        ],
                                    ],
            ])
            // Jobs
            ->job('TestJob', function($task) {
                return $task->daily();
            });
        // Exec
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEmpty($res);
    }
    /**
     * Test interval.
     * @since 1.0.3
     */
    public function testDaily()
    {
        // Prepare
        $scheduler = $this->getScheduler([
                'last_exec_time'    => strtotime('-1 hour'),
                'jobs'              => (object)[
                                        'TestJob'   => (object)[
                                            'time'  => strtotime('-1 day'),
                                        ],
                                    ],
            ])
            // Jobs
            ->job('TestJob', function($task) {
                return $task->daily();
            });
        // Exec
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertNotEmpty($res);
        $this->assertEquals($res, 'EXECUTED!');
    }
    /**
     * Test interval.
     * @since 1.0.3
     */
    public function testEveryTwoDays()
    {
        // Prepare
        $scheduler = $this->getScheduler([
                'last_exec_time'    => strtotime('-1 hour'),
                'jobs'              => (object)[
                                        'TestJob'   => (object)[
                                            'time'  => strtotime('-2 day'),
                                        ],
                                    ],
            ])
            // Jobs
            ->job('TestJob', function($task) {
                return $task->everyTwoDays();
            });
        // Exec
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertNotEmpty($res);
        $this->assertEquals($res, 'EXECUTED!');
    }
    /**
     * Test interval.
     * @since 1.0.3
     */
    public function testEveryTwoDaysNoExecution()
    {
        // Prepare
        $scheduler = $this->getScheduler([
                'last_exec_time'    => strtotime('-1 hour'),
                'jobs'              => (object)[
                                        'TestJob'   => (object)[
                                            'time'  => strtotime('-1 day'),
                                        ],
                                    ],
            ])
            // Jobs
            ->job('TestJob', function($task) {
                return $task->everyTwoDays();
            });
        // Exec
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEmpty($res);
    }
    /**
     * Test interval.
     * @since 1.0.3
     */
    public function testCustom()
    {
        // Prepare
        $scheduler = $this->getScheduler([
                'last_exec_time'    => strtotime('-1 hour'),
                'jobs'              => (object)[
                                        'TestJob'   => (object)[
                                            'time'  => strtotime('-95 minute'),
                                        ],
                                    ],
            ])
            // Jobs
            ->job('TestJob', function($task) {
                return $task->custom(90);
            });
        // Exec
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertNotEmpty($res);
        $this->assertEquals($res, 'EXECUTED!');
    }
    /**
     * Test interval.
     * @since 1.0.3
     */
    public function testCustomNoExecution()
    {
        // Prepare
        $scheduler = $this->getScheduler([
                'last_exec_time'    => strtotime('-1 hour'),
                'jobs'              => (object)[
                                        'TestJob'   => (object)[
                                            'time'  => strtotime('-85 minute'),
                                        ],
                                    ],
            ])
            // Jobs
            ->job('TestJob', function($task) {
                return $task->custom(90);
            });
        // Exec
        ob_start();
        $scheduler->start();
        $res = ob_get_clean();
        // Assert
        $this->assertEmpty($res);
    }
}