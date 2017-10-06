<?php

namespace AOE\SchedulerTimeline\Tests\Unit\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use AOE\SchedulerTimeline\Domain\Model\Log;
use AOE\SchedulerTimeline\Domain\Model\Task;
use AOE\SchedulerTimeline\Domain\Repository\LogRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class LogRepositoryTest
 * @package AOE\SchedulerTimeline\Tests\Unit\Domain\Repository
 */
class LogRepositoryTest extends \Nimut\TestingFramework\TestCase\UnitTestCase
{

    public function setUp()
    {
        // Make sure that timezone is always the same in all testing environments
        date_default_timezone_set('UTC');
    }

    /**
     * @test
     */
    public function findGroupedByTaskIgnoresLogEntriesForDeletedTasks()
    {
        $logCollection = self::getLogCollection();
        $logRepository = $this->getMock(LogRepository::class, array('findAll'), array(new ObjectManager()));
        $logRepository->expects(self::any())->method('findAll')->willReturn($logCollection);

        $logs = $logRepository->findGroupedByTask();

        self::assertCount(3, $logs);
        self::assertSame($logCollection[0]->getTask(), $logs[1]['task']);
        self::assertSame($logCollection[1]->getTask(), $logs[2]['task']);
        // The third log collection item is not returned as it has no Task object attached
        self::assertSame($logCollection[3]->getTask(), $logs[4]['task']);
    }

    /**
     * Gets a collection of Log objects, one without an attached Task object
     *
     * @return array<Log>
     */
    private static function getLogCollection()
    {
        return array(
            self::getLogItem(self::getTaskItem(1)),
            self::getLogItem(self::getTaskItem(2)),
            self::getLogItem(),
            self::getLogItem(self::getTaskItem(4))
        );
    }

    /**
     * Returns a Log object
     *
     * @param Task $task
     * @return Log
     */
    private static function getLogItem(Task $task = null)
    {
        $log = new Log();
        $log->_setProperty('task', $task);

        return $log;
    }

    /**
     * Returns a Task object
     *
     * @param integer $uid
     * @return Task
     */
    private static function getTaskItem($uid = 0)
    {
        $task = new Task();
        $task->_setProperty('uid', $uid);

        return $task;
    }
}
