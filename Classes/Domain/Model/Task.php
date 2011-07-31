<?php

class Tx_SchedulerTimeline_Domain_Model_Task extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var string
	 */
	protected $classname;

	/**
	 * @var string
	 */
	protected $serializedTaskObject;

	public function getClassname() {
		return $this->classname;
	}

	/**
	 * @var tx_scheduler_Task
	 */
	public function getTaskObject() {
		return unserialize($this->serializedTaskObject);
	}

	public function getInfo() {
		$registeredClass = $this->getRegisteredClasses();
		return $registeredClass[$this->getClassname()];
	}

	public function getExtension() {
		$info = $this->getInfo();
		return $info['extension'];
	}

	public function getFilename() {
		$info = $this->getInfo();
		return $info['filename'];
	}

	public function getTitle() {
		$info = $this->getInfo();
		return $info['title'];
	}

	public function getProvider() {
		$info = $this->getInfo();
		return $info['provider'];
	}

	/**
	 * This method a list of all classes that have been registered with the Scheduler
	 * For each item the following information is provided, as an associative array:
	 *
	 * ['extension']	=>	Key of the extension which provides the class
	 * ['filename']		=>	Path to the file containing the class
	 * ['title']		=>	String (possibly localized) containing a human-readable name for the class
	 * ['provider']		=>	Name of class that implements the interface for additional fields, if necessary
	 *
	 * The name of the class itself is used as the key of the list array
	 *
	 * @return array List of registered classes
	 */
	public function getRegisteredClasses() {
		$list = array();
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'] as $class => $registrationInformation) {

				$title         = isset($registrationInformation['title'])         ? $GLOBALS['LANG']->sL($registrationInformation['title'])         : '';
				$description   = isset($registrationInformation['description'])   ? $GLOBALS['LANG']->sL($registrationInformation['description'])   : '';

				$list[$class] = array(
					'extension'     => $registrationInformation['extension'],
					'title'         => $title,
					'description'   => $description,
					'provider'		=> isset($registrationInformation['additionalFields']) ? $registrationInformation['additionalFields'] : ''
				);
			}
		}

		return $list;
	}

}