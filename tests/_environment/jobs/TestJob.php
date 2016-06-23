<?php

use Scheduler\Base\Job;

class TestJob extends Job
{
    public function execute()
    {
        echo 'EXECUTED!';
    }
}