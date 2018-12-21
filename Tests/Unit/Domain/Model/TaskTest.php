<?php

namespace AOE\SchedulerTimeline\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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

use AOE\SchedulerTimeline\Domain\Model\Task;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class TaskTest
 *
 * @package AOE\SchedulerTimeline\Tests\Unit\Domain\Model
 */
class TaskTest extends UnitTestCase
{

    /**
     * @var Task
     */
    protected $subject;

    /**
     * Sets up the test case
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock('AOE\\SchedulerTimeline\\Domain\\Model\\Task', array('dummy'));
    }

    /**
     * @test
     * @return void
     */
    public function getClassReturnsClassNameOfSerializedTaskObject()
    {
        $taskFixture = new \AOE\SchedulerTimeline\Tests\Unit\Domain\Model\Fixtures\EmptyTask();
        $serializedTaskFixture = serialize($taskFixture);

        $this->subject->_set('serializedTaskObject', $serializedTaskFixture);
        $this->assertEquals('AOE\\SchedulerTimeline\\Tests\\Unit\\Domain\\Model\\Fixtures\\EmptyTask', $this->subject->getClassname());
    }

    /**
     * @test
     * @return void
     */
    public function getTaskObjectReturnsClassOfSerializedTaksObject()
    {
        $taskFixture = new \AOE\SchedulerTimeline\Tests\Unit\Domain\Model\Fixtures\EmptyTask();
        $serializedTaskFixture = serialize($taskFixture);

        $this->subject->_set('serializedTaskObject', $serializedTaskFixture);
        $this->assertEquals($taskFixture, $this->subject->getTaskObject());
    }

    /**
     * @test
     * @return void
     */
    public function getLogFilePathReturnsFalseIfSerializedTaskObjectDoesntHaveMethodGetLogFilePath()
    {
        $taskFixture = new \AOE\SchedulerTimeline\Tests\Unit\Domain\Model\Fixtures\EmptyTask();
        $serializedTaskFixture = serialize($taskFixture);

        $this->subject->_set('serializedTaskObject', $serializedTaskFixture);
        $this->assertFalse($this->subject->getLogFilePath());
    }

    /**
     * @test
     * @return void
     */
    public function getLogFilePathReturnsPathFromSerializedTaskObject()
    {
        $taskFixture = new \AOE\SchedulerTimeline\Tests\Unit\Domain\Model\Fixtures\TaskWithLogFilePath();
        $serializedTaskFixture = serialize($taskFixture);

        $this->subject->_set('serializedTaskObject', $serializedTaskFixture);
        $this->assertEquals('some/file/path', $this->subject->getLogFilePath());
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionCode 1450187123
     * @return void
     */
    public function getTaskObjectThrowsExceptionIfSerializedTaskObjectIsNotSet()
    {
        $this->subject->getTaskObject();
    }
}
