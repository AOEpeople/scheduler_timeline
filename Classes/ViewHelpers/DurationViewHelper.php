<?php

class Tx_SchedulerTimeline_ViewHelpers_DurationViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Render
	 *
	 * @param int $duration
	 * @return string rendered tag
	 */
	public function render($duration) {

		$hours = intval(intval($duration) / 3600);
		$hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
		$minutes = intval(($duration / 60) % 60);
		$seconds = intval($duration % 60);

		$result = $seconds;
		if (intval($minutes) || intval($hours)) {
			$seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
			$result = $minutes .':'.$seconds;
		}
		if (intval($hours)) {
			$minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
			$result = $hours .':'.$minutes .':'.$seconds;
		}
		return $result;
	}

}

