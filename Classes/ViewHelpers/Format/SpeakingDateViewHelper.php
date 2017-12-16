<?php
namespace AOE\SchedulerTimeline\ViewHelpers\Format;

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

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class SpeakingDateViewHelper
 *
 * @package AOE\SchedulerTimeline\ViewHelpers\Format
 */
class SpeakingDateViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    /**
     * Initializes the arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('timestamp', 'string', 'Timestamp', true);
        $this->registerArgument('defaultFormat', 'string', 'Default time format', false, 'Y.m.d H:i');
        $this->registerArgument('todayFormat', 'string', 'Time format for today', false, 'H:i');
        $this->registerArgument('tomorrowFormat', 'string', 'Time format for tomorrow', false, '\T\o\m\m\o\r\o\w, H:i');
        $this->registerArgument('yesterdayFormat', 'string', 'Time format for yesterday', false, '\Y\e\s\t\e\r\d\a\y, H:i');
    }

    /**
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {

        $timestamp = $arguments['timestamp'];
        $defaultFormat = $arguments['defaultFormat'];
        $todayFormat = $arguments['todayFormat'];
        $tomorrowFormat = $arguments['tomorrowFormat'];
        $yesterdayFormat = $arguments['yesterdayFormat'];

        $day = date('Ymd', $timestamp);
        if ($todayFormat && (date('Ymd') == $day)) {
            $result = date($todayFormat, $timestamp);
        } elseif ($tomorrowFormat && (date('Ymd', strtotime('+1 day')) == $day)) {
            $result = date($tomorrowFormat, $timestamp);
        } elseif ($yesterdayFormat && (date('Ymd', strtotime('-1 day')) == $day)) {
            $result = date($yesterdayFormat, $timestamp);
        } else {
            $result = date($defaultFormat, $timestamp);
        }
        return $result;
    }
}
