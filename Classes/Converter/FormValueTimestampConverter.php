<?php

namespace AOE\SchedulerTimeline\Converter;

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

class FormValueTimestampConverter
{
    /**
     * @param string $value
     * @return string
     */
    public static function convertValueToStrToTimeValue(string $value)
    {
        $timeArray = preg_split('#(?<=\d)(?=[a-z])#i', $value);
        $timeArray[0] = $timeArray[0] ?? '1';
        $timeArray[1] = $timeArray[1] ?? 'hour';
        return $timeArray[0] . ' ' . self::getFullLabel($timeArray[1]);
    }

    /**
     * @param string $label
     * @return string
     */
    private function getFullLabel(string $label)
    {
        switch ($label) {
            case 'd':
                return 'day';
            case 'w':
                return 'week';
            case 'y':
                return 'year';
            case 'h':
            default:
                return 'hour';

        }
    }
}