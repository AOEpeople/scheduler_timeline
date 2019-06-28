<?php

namespace AOE\SchedulerTimeline\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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

/**
 * Class Task
 *
 * @package AOE\SchedulerTimeline\Domain\Model
 */
class Task extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected $serializedTaskObject;

    /**
     * @var string
     */
    protected $logFileContent;

    /**
     * @return string
     */
    public function getSerializedTaskObject(): string
    {
        return $this->serializedTaskObject;
    }

    /**
     * @param string $serializedTaskObject
     */
    public function setSerializedTaskObject(string $serializedTaskObject)
    {
        $this->serializedTaskObject = $serializedTaskObject;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassname()
    {
        return get_class($this->getTaskObject());
    }

    /**
     * Get task object
     *
     * @return \TYPO3\CMS\Scheduler\Task\AbstractTask
     * @throws \Exception
     */
    public function getTaskObject()
    {
        $taskObject = unserialize($this->serializedTaskObject);
        if (!is_object($taskObject)) {
            throw new \Exception('Inconsitent data: There is no serialized task object in the Task', 1450187123);
        }
        return $taskObject;
    }

    /**
     * Get log file path
     * Expects $taskObject->getLogFilePath() to return the path
     *
     * @return string|bool
     */
    public function getLogFilePath()
    {
        $taskObject = $this->getTaskObject();
        if ($taskObject && is_callable(array($taskObject, 'getLogFilePath'))) {
            return $taskObject->getLogFilePath();
        }
        return false;
    }

    /**
     * Get log file content
     * (If a log file is available. See $this->getLogFilePath())
     *
     * @return string
     */
    public function getLogFileContent()
    {
        if (is_null($this->logFileContent)) {
            $this->logFileContent = '';
            $logFilePath = $this->getLogFilePath();
            if ($logFilePath && is_file($logFilePath)) {
                $this->logFileContent = shell_exec('tail -n 20 ' . escapeshellarg($logFilePath));
            }
        }
        return $this->logFileContent;
    }

    /**
     * Get info for this task
     *
     * @return array
     */
    public function getInfo()
    {
        $registeredClasses = $this->getRegisteredClasses();
        return $registeredClasses[$this->getClassname()];
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        $info = $this->getInfo();
        return $info['extension'];
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        $info = $this->getInfo();
        return $info['filename'];
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        $info = $this->getInfo();
        return $info['title'];
    }

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        $info = $this->getInfo();
        return $info['provider'];
    }

    /**
     * This method a list of all classes that have been registered with the Scheduler
     * For each item the following information is provided, as an associative array:
     *
     * ['extension']    =>  Key of the extension which provides the class
     * ['filename']     =>  Path to the file containing the class
     * ['title']        =>  String (possibly localized) containing a human-readable name for the class
     * ['provider']     =>  Name of class that implements the interface for additional fields, if necessary
     *
     * The name of the class itself is used as the key of the list array
     *
     * @return array List of registered classes
     */
    protected function getRegisteredClasses()
    {
        $list = array();
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'] as $class => $registrationInformation) {
                $title         = isset($registrationInformation['title'])         ? $GLOBALS['LANG']->sL($registrationInformation['title'])         : '';
                $description   = isset($registrationInformation['description'])   ? $GLOBALS['LANG']->sL($registrationInformation['description'])   : '';

                $list[$class] = array(
                    'extension'     => $registrationInformation['extension'],
                    'title'         => $title,
                    'description'   => $description,
                    'provider'        => isset($registrationInformation['additionalFields']) ? $registrationInformation['additionalFields'] : ''
                );
            }
        }

        return $list;
    }
}
