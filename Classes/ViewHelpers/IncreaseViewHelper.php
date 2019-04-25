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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class IncreaseViewHelper
 *
 * @package AOE\SchedulerTimeline\ViewHelpers
 */
class IncreaseViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    /**
     * View helper returns HTML, thus we need to disable output escaping
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initializes the arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('start', 'string', 'Start time', true);
        $this->registerArgument('end', 'string', 'End time', true);
        $this->registerArgument('interval', 'string', 'Interval of hours', false, '1');
        $this->registerArgument('iterator', 'string', 'Iterator of hours', false, '1');
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
        /** @var TemplateVariableContainer $templateVariableContainer */
        $templateVariableContainer = GeneralUtility::makeInstance(TemplateVariableContainer::class);

        $start = $arguments['start'];
        $end = $arguments['end'];
        $interval = $arguments['interval'];
        $iterator = $arguments['iterator'];

        $result = '';
        for ($i = $start; $i < $end; $i += $interval) {
            $templateVariableContainer->add($iterator, $i);
            $result .= $renderChildrenClosure();
            $templateVariableContainer->remove($iterator);
        }
        return $result;
    }
}
