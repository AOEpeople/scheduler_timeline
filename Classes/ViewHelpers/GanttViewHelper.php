<?php

class Tx_SchedulerTimeline_ViewHelpers_GanttViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * Render
	 *
	 * @param Tx_SchedulerTimeline_Domain_Model_Log $log
	 * @param int $starttime
	 * @param int $zoom
	 * @return string rendered tag
	 */
	public function render(Tx_SchedulerTimeline_Domain_Model_Log $log, $starttime, $zoom) {

		$duration = $log->getDuration() / $zoom;
		$duration = ceil($duration / 4) * 4 - 1; // round to numbers dividable by 4, then remove 1 px border
		$duration = max($duration, 3);

		$offset = ($log->getStarttime() - $starttime) / $zoom;
		if ($offset < 0) {
			// cut bar
			$duration += $offset;
			$offset = 0;
		}

		$duration = min($duration, 3*240);

		$this->tag->addAttribute('style', sprintf('width: %spx; left: %spx;', $duration, $offset));
		$class = 'task';
		if ($log->getException()) {
			$class .= ' error';
		}
		if ($log->isRunning()) {
			$class .= ' running';
		}
		$this->tag->addAttribute('id', 'uid_'.$log->getUid());
		$this->tag->addAttribute('class', $class);
		$this->tag->addAttribute('title', $log->getTimespan());

		$this->tag->setContent($this->renderChildren());

		return $this->tag->render();
	}

}

