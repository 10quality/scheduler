<?php

use Scheduler\Base\Job;

class ExceptionJob extends Job
{
    public function execute()
    {
        throw new Exception('Test error.');
    }
}