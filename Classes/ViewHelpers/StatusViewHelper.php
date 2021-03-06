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
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class StatusViewHelper
 *
 * @package AOE\SchedulerTimeline\ViewHelpers
 */
class StatusViewHelper extends AbstractViewHelper implements CompilableInterface
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
        $this->registerArgument('status', 'string', 'the status of the process', true);
    }

    /**
     * Prints status html for process
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {

        $status = $arguments['status'];

        switch ($status) {
            case Log::STATUS_SUCCESS:
                $result = '<span class="bar-green"><span>' . $status . '</span></span>';
                break;
            case Log::STATUS_PENDING:
                $result = '<span class="bar-lightgray"><span>' . $status . '</span></span>';
                break;
            case Log::STATUS_RUNNING:
                $result = '<span class="bar-yellow"><span>' . $status . '</span></span>';
                break;
            case Log::STATUS_MISSED:
                $result = '<span class="bar-orange"><span>' . $status . '</span></span>';
                break;
            case Log::STATUS_ERROR:
                $result = '<span class="bar-red"><span>' . $status . '</span></span>';
                break;
            default:
                $result = $status;
        }
        return $result;
    }
}
