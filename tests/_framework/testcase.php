<?php

use Scheduler\Scheduler;

class Scheduler_TestCase extends PHPUnit_Framework_TestCase
{
    public function getSchedulerMock($session = null)
    {
        return $this->getMockBuilder(Scheduler::class)
            ->setConstructorArgs([
                [
                    'jobs'      => ['path' => JOBS_PATH],
                    'session'   => ['driver' => 'callable'],
                ],
                function() use(&$session) {
                    return new SessionMock(empty($session) ? [] : $session);
                }
            ])
            ->getMock();
    }
    public function getScheduler($session = null)
    {
        return Scheduler::ready(
            [
                'jobs'      => ['path' => JOBS_PATH],
                'session'   => ['driver' => 'callable'],
            ],
            function() use(&$session) {
                return new SessionMock(empty($session) ? [] : $session);
            }
        );
    }
    
}