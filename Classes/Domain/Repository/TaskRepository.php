<?php

class Tx_SchedulerTimeline_Domain_Repository_TaskRepository extends Tx_Extbase_Persistence_Repository {

	/**
	 * Initialize object
	 * Ignore storege pid
	 *
	 * @return void
	 */
	public function initializeObject() {
		$querySettings = $this->objectManager->create('Tx_Extbase_Persistence_Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		$this->setDefaultQuerySettings($querySettings);
	}

}