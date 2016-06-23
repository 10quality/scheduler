<?php
class SchedulerTest extends PHPUnit_Framework_TestCase
{
    public function testJob()
    {
        // PREPARE
        $return = exec('php ' . ENV_PATH . '/scheduler.php');
        // ASSERT
        $this->assertEquals($return, 'EXECUTED!');
    }
}