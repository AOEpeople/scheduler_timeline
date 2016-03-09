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

/**
 * Class LogTest
 * @package AOE\SchedulerTimeline\Tests\Unit\Domain\Model
 */
class LogTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Log
     *
     * @var \AOE\SchedulerTime\Domain|Model\Log
     */
    protected $subject;

    public function setUp()
    {
        // Make sure that timezone is always the same in all testing environments
        date_default_timezone_set('GMT+0');
        $this->subject = new \AOE\SchedulerTimeline\Domain\Model\Log();
    }

    /**
     * @test
     */
    public function checkInitiationOfSubjectReturnObjectOfDomainModelLog()
    {
        $this->assertInstanceOf(
            'AOE\SchedulerTimeline\Domain\Model\Log',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStarttimeReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getStarttime()
        );
    }

    /**
     * @test
     */
    public function getEndtimeReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getEndtime()
        );
    }

    /**
     * @test
     */
    public function getExceptionReturnsInitialValueForString()
    {
        $this->assertSame(
            false,
            $this->subject->getException()
        );
    }

    /**
     * @test
     */
    public function getReturnmessageReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getReturnmessage()
        );
    }

    /**
     * @test
     */
    public function getProcessIdReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getProcessid()
        );
    }

    /**
     * @test
     */
    public function getTaskReturnsNull()
    {
        $this->assertNull($this->subject->getTask());
    }

    /**
     * @test
     * @dataProvider getDurationReturnsDurationInSecondsDataProvider()
     */
    public function getDurationReturnsDurationInSeconds($starttime, $endtime, $expectedDuration)
    {
        $stub = $this->getAccessibleMock(
            'AOE\\SchedulerTimeline\\Domain\\Model\\Log',
            array('getStarttime', 'getEndtime', 'getCurrentTime')
        );
        $stub->expects($this->any())->method('getStarttime')->will($this->returnValue($starttime));
        $stub->expects($this->any())->method('getEndtime')->will($this->returnValue($endtime));
        $stub->expects($this->any())->method('getCurrentTime')->will($this->returnValue(1000));

        $this->assertSame($expectedDuration, $stub->getDuration());
    }

    /**
     * @return array
     */
    public function getDurationReturnsDurationInSecondsDataProvider()
    {
        return array(
            'With start- and endtime' => array(
                'starttime' => 0,
                'endtime' => 10,
                'expectedDuration' => 10
            ),
            'With starttime but no endtime' => array(
                'starttime' => 0,
                'endtime' => null,
                'expectedDuration' => 1000
            ),
            'With same start- and endtime' => array(
                'starttime' => 100,
                'endtime' => 100,
                'expectedDuration' => 0
            ),
            'With both start- and endtime being zero' => array(
                'starttime' => 0,
                'endtime' => 0,
                'expectedDuration' => 1000
            ),
            'With endtime lower than starttime' => array(
                'starttime' => 100,
                'endtime' => 10,
                'expectedDuration' => -90
            ),
        );
    }

    /**
     * @test
     * @dataProvider isRunningReturnsBooleanDataProvider()
     */
    public function isRunningReturnsBoolean($endtime, $isRunning)
    {
        $stub = $this->getMock('AOE\\SchedulerTimeline\\Domain\\Model\\Log', array('getEndtime'));
        $stub->expects($this->any())->method('getEndtime')->will($this->returnValue($endtime));
        $this->assertSame(
            $isRunning,
            $stub->isRunning()
        );
    }

    /**
     * @return array
     */
    public function isRunningReturnsBooleanDataProvider()
    {
        return array(
            'Endtime set to zero' => array(
                'endtime' => 0,
                'isRunning' => true
            ),
            'Endtime set to null' => array(
                'entime' => null,
                'isRunning' => true
            ),
            'Endtime set' => array(
                'endtime' => '10',
                'isRunning' => false
            )
        );
    }

    /**
     * @test
     * @dataProvider getFormattedDateFromTimestampReturnsStringDataProvider()
     */
    public function getFormattedDateFromTimestampReturnsString($timestamp, $expectedFormattedDate)
    {
        $stub = $this->getAccessibleMock('AOE\\SchedulerTimeline\\Domain\\Model\\Log', array('dummy'));

        $this->assertSame(
            $expectedFormattedDate,
            $stub->_call('getFormattedDateFromTimestamp', $timestamp)
        );
    }

    /**
     * @return array
     */
    public function getFormattedDateFromTimestampReturnsStringDataProvider()
    {
        return array(
            'Empty timestamp' => array(
                'timestamp' => 0,
                'expectedFormattedDate' => '00:00'
            ),
            'Timestamp 2015-10-13 16:26' => array(
                'timestamp' => strtotime('2015-10-13 16:26'),
                'expectedFormattedDate' => '16:26'
            ),
            'Timestamp 2015-11-13 10:13' => array(
                'timestamp' => strtotime('2015-11-13 10:13'),
                'expectedFormattedDate' => '10:13'
            ),
        );
    }

    /**
     * @test
     *
     * @param $isRunning
     * @param $starttime
     * @param $starttimeFormatted
     * @param $endtime
     * @param $endtimeFormatted
     * @param $expectedString
     *
     * @dataProvider getTimespanReturnsStringDataProvider()
     */
    public function getTimespanReturnsString($isRunning, $starttime, $starttimeFormatted, $endtime, $endtimeFormatted, $expectedString)
    {
        $stub = $this->getAccessibleMock(
            'AOE\\SchedulerTimeline\\Domain\\Model\\Log',
            array('isRunning', 'getStarttime', 'getEndtime', 'getFormattedDateFromTimestamp')
        );
        $stub->expects($this->any())->method('isRunning')->will($this->returnValue($isRunning));
        $stub->expects($this->any())->method('getStarttime')->will($this->returnValue($starttime));
        $stub->expects($this->any())->method('getEndtime')->will($this->returnValue($endtime));

        $returnMap = array(
            array($starttime, $starttimeFormatted),
            array($endtime, $endtimeFormatted)
        );

        $stub->expects($this->any())->method('getFormattedDateFromTimestamp')->will($this->returnValueMap($returnMap));

        $this->assertSame(
            $expectedString,
            $stub->getTimespan()
        );
    }

    /**
     * @return array
     */
    public function getTimespanReturnsStringDataProvider()
    {
        return array(
            'Task zero as starttime, task still running no endtime' => array(
                'isRunning' => true,
                'starttime' => 0,
                'starttimeFormatted' => '00:00',
                'endtime' => null,
                'endtimeFormatted' => null,
                'expectedString' => '00:00 - (still running)'
            ),
            'Task with starttime 2015-10-13 16:26 and no endtime' => array(
                'isRunning' => true,
                'starttime' => strtotime('2015-10-13 16:26'),
                'starttimeFormatted' => '16:26',
                'endtime' => null,
                'endtimeFormatted' => null,
                'expectedString' => '16:26 - (still running)'
            ),
            'Task with starttime 2015-11-13 9:26 and endtime 2015-11-13 10:13' => array(
                'isRunning' => false,
                'starttime' => strtotime('2015-11-13 9:26'),
                'starttimeFormatted' => '09:26',
                'endtime' => strtotime('2015-11-13 10:13'),
                'endtimeFormatted' => '10:13',
                'expectedString' => '09:26 - 10:13'
            ),
        );
    }

    /**
     * @test
     * @dataProvider getStatusReturnStringDataProvider()
     */
    public function getStatusReturnString($exception, $isRunning, $expectedStatus)
    {
        $stub = $this->getAccessibleMock('AOE\\SchedulerTimeline\\Domain\\Model\\Log', array('isRunning', 'getException'));
        $stub->expects($this->any())->method('isRunning')->will($this->returnValue($isRunning));
        $stub->expects($this->any())->method('getException')->will($this->returnValue($exception));

        $this->assertSame(
            $expectedStatus,
            $stub->getStatus()
        );
    }

    /**
     * @return array
     */
    public function getStatusReturnStringDataProvider()
    {
        return array(
            'Task is still running' => array(
                'exception' => null,
                'isRunning' => true,
                'expectedStatus' => \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_RUNNING
            ),
            'Task is done' => array(
                'exception' => null,
                'isRunning' => false,
                'expectedStatus' => \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_SUCCESS
            ),
            'Task failed with exception' => array(
                'exception' => 's:19:"NotWorkingException"',
                'isRunning' => false,
                'expectedStatus' => \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_ERROR
            )
        );
    }
}
