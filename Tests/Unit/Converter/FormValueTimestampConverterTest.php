<?php

namespace AOE\SchedulerTimeline\Tests\Unit\Converter;

/*
 * (c) 2020 AOE GmbH <dev@aoe.com>
 *
 * This file is part of the TYPO3 Scheduler Timeline Extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use AOE\SchedulerTimeline\Converter\FormValueTimestampConverter;
use Nimut\TestingFramework\TestCase\UnitTestCase;


class formValueTimestampConverterTest extends UnitTestCase
{
    /**
     * @test
     * @dataProvider convertValueToStrToTimeValueDataProvider
     * @return void
     */
    public function convertValueToStrToTimeValueTest(string $label, string $expected)
    {
        self::assertSame(
            $expected,
            FormValueTimestampConverter::convertValueToStrToTimeValue($label)
        );

    }

    /**
     * @return array
     */
    public function convertValueToStrToTimeValueDataProvider()
    {
        return [
            '1 Hours' => [
                'label' => '1h',
                'expected' => '1 hour'
            ],
            '3 Hours' => [
                'label' => '3h',
                'expected' => '3 hour'
            ],
            '1 Day' => [
                'label' => '1d',
                'expected' => '1 day'
            ],
            '3 Days' => [
                'label' => '3d',
                'expected' => '3 day'
            ],
            '1 Week' => [
                'label' => '1w',
                'expected' => '1 week'
            ],
            '2 Weeks' => [
                'label' => '2w',
                'expected' => '2 week'
            ],
            '1 Year' => [
                'label' => '1y',
                'expected' => '1 year'
            ],
        ];
    }
}