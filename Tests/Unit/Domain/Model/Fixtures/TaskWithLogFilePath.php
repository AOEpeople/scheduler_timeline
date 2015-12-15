<?php

namespace AOE\SchedulerTimeline\Tests\Unit\Domain\Model\Fixtures;

/**
 * Class TaskWithLogFilePath
 *
 * @package AOE\SchedulerTimeline\Tests\Unit\Domain\Model\Fixtures
 */
class TaskWithLogFilePath
{
    /**
     * @return string
     */
    public function getLogFilePath()
    {
        return 'some/file/path';
    }
}
