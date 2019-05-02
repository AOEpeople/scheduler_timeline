<?php
namespace AOE\SchedulerTimeline\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 AOE GmbH <dev@aoe.com>
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class GanttViewHelper
 *
 * @package AOE\SchedulerTimeline\ViewHelpers
 */
class GanttViewHelper extends AbstractTagBasedViewHelper implements CompilableInterface
{

    use CompileWithRenderStatic;

    /**
     * Initializes the arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('log', '\AOE\SchedulerTimeline\Domain\Model\Log', 'Log Record', true);
        $this->registerArgument('starttime', 'string', 'Start time', true);
        $this->registerArgument('zoom', 'string', 'Zoom level', true);
    }

    /**
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        /** @var TagBuilder $tag */
        $tag = GeneralUtility::makeInstance(TagBuilder::class);

        /** @var Log $log */
        $log = $arguments['log'];
        $zoom = $arguments['zoom'];
        $startTime = $arguments['starttime'];

        $duration = $log->getDuration() / $zoom;
        $duration = ceil($duration / 4) * 4 - 1; // round to numbers dividable by 4, then remove 1 px border
        $duration = max($duration, 3);

        $offset = ($log->getStarttime() - $startTime) / $zoom;
        if ($offset < 0) { // cut bar
            $duration += $offset;
            $offset = 0;
        }
        $tag->setTagName('div');
        $tag->addAttribute('style', sprintf('width: %spx; left: %spx;', $duration, $offset));
        $tag->addAttribute('class', 'task ' . $log->getStatus());
        $tag->addAttribute('id', 'uid_' . $log->getUid());
        $tag->setContent($renderChildrenClosure());
        return $tag->render();
    }
}
