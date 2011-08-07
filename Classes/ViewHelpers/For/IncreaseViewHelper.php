<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Fabrizio Branca <typo3@fabrizio-branca.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * GanttViewHelper
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
class Tx_SchedulerTimeline_ViewHelpers_For_IncreaseViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Render
	 *
	 * @param string $start
	 * @param string $end
	 * @param string $interval
	 * @param string $iterator
	 * @return string
	 */
	public function render($start, $end, $interval='1', $iterator='i') {
		$result = '';
		for ($i=$start; $i<$end; $i+=$interval) {
			$this->templateVariableContainer->add($iterator, $i);
    		$result .= $this->renderChildren();
    		$this->templateVariableContainer->remove($iterator);
    	}
		return $result;
	}

}

