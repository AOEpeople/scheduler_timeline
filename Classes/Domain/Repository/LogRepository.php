<?php

class Tx_SchedulerTimeline_Domain_Repository_LogRepository extends Tx_Extbase_Persistence_Repository {

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

	public function findByTime($starttime, $endtime) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->logicalOr(
					$query->greaterThanOrEqual('endtime', $starttime),
					$query->equals('endtime', 0)
				),
				$query->lessThanOrEqual('starttime', $endtime)
			)
		);
		$query->setOrderings(array('starttime' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
		return $query->execute();
	}

}