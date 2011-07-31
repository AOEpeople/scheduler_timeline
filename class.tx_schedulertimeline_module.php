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
 * Module 'TYPO3 Scheduler administration module' for the 'scheduler' extension.
 *
 * @author		Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package		TYPO3
 * @subpackage	tx_schedulertimeline
 */
class tx_schedulertimeline_Module extends t3lib_SCbase {

	/**
	 * Back path to typo3 main dir
	 *
	 * @var	string		$backPath
	 */
	public $backPath;

	/**
	 * Array containing submitted data when editing or adding a task
	 *
	 * @var	array		$submittedData
	 */
	protected $submittedData = array();

	/**
	 * Array containing all messages issued by the application logic
	 * Contains the error's severity and the message itself
	 *
	 * @var	array	$messages
	 */
	protected $messages = array();

	/**
	 * @var	string	Key of the CSH file
	 */
	protected $cshKey;

	/**
	 *
	 * @var	tx_scheduler	Local scheduler instance
	 */
	protected $scheduler;

	/**
	 * Constructor
	 *
	 * @return tx_scheduler_Module
	 */
	public function __construct() {
		$this->backPath = $GLOBALS['BACK_PATH'];
			// Set key for CSH
		$this->cshKey = '_MOD_' . $GLOBALS['MCONF']['name'];
	}

	/**
	 * Initializes the backend module
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

			// Initialize document
		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->setModuleTemplate(t3lib_extMgm::extPath('scheduler') . 'mod1/mod_template.html');
		$this->doc->getPageRenderer()->addCssFile(t3lib_extMgm::extRelPath('scheduler') . 'res/tx_scheduler_be.css');
		$this->doc->backPath = $this->backPath;
		$this->doc->bodyTagId = 'typo3-mod-php';
		$this->doc->bodyTagAdditions = 'class="tx_scheduler_mod1"';

			// Create scheduler instance
		$this->scheduler = t3lib_div::makeInstance('tx_scheduler');
	}

	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return void
	 */
	public function menuConfig() {
		$this->MOD_MENU = array(
			'function' => array(
				'timeline' => $GLOBALS['LANG']->getLL('function.timeline'),
			)
		);

		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 *
	 * @return void
	 */
	public function main() {
			// Access check!
			// The page will show only if user has admin rights
		if ($GLOBALS['BE_USER']->user['admin']) {

				// Set the form
			$this->doc->form = '<form name="tx_scheduler_form" id="tx_scheduler_form" method="post" action="">';

				// JavaScript for main function menu
			$this->doc->JScode = '
				<script language="javascript" type="text/javascript">
					script_ended = 0;
					function jumpToUrl(URL) {
						document.location = URL;
					}
				</script>
			';
			$this->doc->getPageRenderer()->addInlineSetting('scheduler', 'runningIcon', t3lib_extMgm::extRelPath('scheduler') . 'res/gfx/status_running.png');

				// Prepare main content
			$this->content  = $this->doc->header(
				$GLOBALS['LANG']->getLL('function.' . $this->MOD_SETTINGS['function'])
			);
			$this->content .= $this->doc->spacer(5);
			$this->content .= $this->getModuleContent();
		} else {
				// If no access, only display the module's title
			$this->content  = $this->doc->header($GLOBALS['LANG']->getLL('title'));
			$this->content .= $this->doc->spacer(5);
		}

			// Place content inside template
		$content = $this->doc->moduleBody(
			array(),
			$this->getDocHeaderButtons(),
			$this->getTemplateMarkers()
		);

			// Renders the module page
		$this->content = $this->doc->render(
			$GLOBALS['LANG']->getLL('title'),
			$content
		);
	}

	/**
	 * Generate the module's content
	 *
	 * @return string HTML of the module's main content
	 */
	protected function getModuleContent() {
		$content = '';
		$sectionTitle = '';

			// Handle chosen action
		switch((string)$this->MOD_SETTINGS['function'])	{
			case 'timeline':
				$content .= 'HELLO WORLD';
				break;
		}

			// Wrap the content in a section
		return $this->doc->section($sectionTitle, '<div class="tx_schedulertimeline_mod1">' . $content . '</div>', 0, 1);
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
	protected static function getRegisteredClasses() {
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

	/**
	 * This method actually prints out the module's HTML content
	 *
	 * @return void
	 */
	public function render() {
		echo $this->content;
	}


	



	

	/**
	 * Display the current server's time along with a help text about server time
	 * usage in the Scheduler
	 *
	 * @return string HTML to display
	 */
	protected function displayServerTime() {
			// Get the current time, formatted
		$dateFormat = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'] . ' T (e';
		$now = date($dateFormat) . ', GMT ' . date('P') . ')';
			// Display the help text
		$serverTime  = '<h4>' . $GLOBALS['LANG']->getLL('label.serverTime') . '</h4>';
		$serverTime .= '<p>' . $GLOBALS['LANG']->getLL('msg.serverTimeHelp') . '</p>';
		$serverTime .= '<p>' . sprintf($GLOBALS['LANG']->getLL('msg.serverTime'), $now) . '</p>';
		return $serverTime;
	}




	/**
	 * Assemble display of list of scheduled tasks
	 *
	 * @return string Table of pending tasks
	 */
	protected function listTasks() {
			// Define display format for dates
		$dateFormat = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'];
		$content = '';

			// Get list of registered classes
		$registeredClasses = self::getRegisteredClasses();

			// Get all registered tasks
		$query = array(
			'SELECT'  => '*',
			'FROM'    => 'tx_scheduler_task',
			'WHERE'   => '1=1',
			'ORDERBY' => 'nextexecution'
		);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($query);
		$numRows = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			// No tasks defined, display information message
		if ($numRows == 0) {
				/** @var t3lib_FlashMessage $flashMessage */
			$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage',
				$GLOBALS['LANG']->getLL('msg.noTasks'),
				'',
				t3lib_FlashMessage::INFO
			);
			$content .= $flashMessage->render();
		} else {
				// Load ExtJS framework and specific JS library
				/** @var $pageRenderer t3lib_PageRenderer */
			$pageRenderer = $this->doc->getPageRenderer();
			$pageRenderer->loadExtJS();
			$pageRenderer->addJsFile(t3lib_extMgm::extRelPath('scheduler') . 'res/tx_scheduler_be.js');

				// Initialise table layout
			$tableLayout = array(
				'table' => array(
					'<table border="0" cellspacing="1" cellpadding="2" class="typo3-dblist">', '</table>'
				),
				'0'     => array(
					'tr'     => array('<tr class="t3-row-header">', '</tr>'),
					'defCol' => array('<td>', '</td>'),
					'1'      => array('<td style="width: 36px;">', '</td>'),
					'3'      => array('<td colspan="2">', '</td>'),
				),
				'defRow' => array(
					'tr'     => array('<tr class="db_list_normal">', '</tr>'),
					'defCol' => array('<td>', '</td>'),
					'1'      => array('<td class="right">', '</td>'),
					'2'      => array('<td class="right">', '</td>'),
				)
			);
			$disabledTaskRow = array (
				'tr'     => array('<tr class="db_list_normal disabled">', '</tr>'),
				'defCol' => array('<td>', '</td>'),
				'1'      => array('<td class="right">', '</td>'),
				'2'      => array('<td class="right">', '</td>'),
			);
			$rowWithSpan = array (
				'tr'     => array('<tr class="db_list_normal">', '</tr>'),
				'defCol' => array('<td>', '</td>'),
				'1'      => array('<td class="right">', '</td>'),
				'2'      => array('<td class="right">', '</td>'),
				'3'      => array('<td colspan="6">', '</td>'),
			);
			$table = array();
			$tr = 0;

				// Header row
			$table[$tr][] = '<a href="#" onclick="toggleCheckboxes();" title="' . $GLOBALS['LANG']->getLL('label.checkAll', TRUE) . '" class="icon">' .
				t3lib_iconWorks::getSpriteIcon('actions-document-select') .
				'</a>';
			$table[$tr][] = '&nbsp;';
			$table[$tr][] = $GLOBALS['LANG']->getLL('label.id');
			$table[$tr][] = $GLOBALS['LANG']->getLL('task');
			$table[$tr][] = $GLOBALS['LANG']->getLL('label.type');
			$table[$tr][] = $GLOBALS['LANG']->getLL('label.frequency');
			$table[$tr][] = $GLOBALS['LANG']->getLL('label.parallel');
			$table[$tr][] = $GLOBALS['LANG']->getLL('label.lastExecution');
			$table[$tr][] = $GLOBALS['LANG']->getLL('label.nextExecution');
			$tr++;

				// Loop on all tasks
			while (($schedulerRecord = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
					// Define action icons
				$editAction = '<a href="' . $GLOBALS['MCONF']['_'] . '&CMD=edit&tx_scheduler[uid]=' . $schedulerRecord['uid'] . '" title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:edit', TRUE) . '" class="icon">' . t3lib_iconWorks::getSpriteIcon('actions-document-open') . '</a>';
				$deleteAction = '<a href="' . $GLOBALS['MCONF']['_'] . '&CMD=delete&tx_scheduler[uid]=' . $schedulerRecord['uid'] . '" onclick="return confirm(\'' . $GLOBALS['LANG']->getLL('msg.delete') . '\');" title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:delete', TRUE) . '" class="icon">' . t3lib_iconWorks::getSpriteIcon('actions-edit-delete') . '</a>';
				$stopAction = '<a href="' . $GLOBALS['MCONF']['_'] . '&CMD=stop&tx_scheduler[uid]=' . $schedulerRecord['uid'] . '" onclick="return confirm(\'' . $GLOBALS['LANG']->getLL('msg.stop') . '\');" title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:stop', TRUE) . '" class="icon"><img ' . t3lib_iconWorks::skinImg($this->backPath, t3lib_extMgm::extRelPath('scheduler') . '/res/gfx/stop.png') . ' alt="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:stop') . '" /></a>';
					// Define some default values
				$lastExecution = '-';
				$isRunning = FALSE;
				$executionStatus = 'scheduled';
				$executionStatusOutput = '';
				$name = '';
				$nextDate = '-';
				$execType = '-';
				$frequency = '-';
				$multiple = '-';
				$startExecutionElement = '&nbsp;';

					// Restore the serialized task and pass it a reference to the scheduler object
					/** @var $task tx_scheduler_Task */
				$task = unserialize($schedulerRecord['serialized_task_object']);

					// Assemble information about last execution
				$context = '';
				if (!empty($schedulerRecord['lastexecution_time'])) {
					$lastExecution = date($dateFormat, $schedulerRecord['lastexecution_time']);
					if ($schedulerRecord['lastexecution_context'] == 'CLI') {
						$context = $GLOBALS['LANG']->getLL('label.cron');
					} else {
						$context = $GLOBALS['LANG']->getLL('label.manual');
					}
					$lastExecution .= ' (' . $context . ')';
				}

				if ($this->scheduler->isValidTaskObject($task)) {
					// The task object is valid

					$name = htmlspecialchars($registeredClasses[$schedulerRecord['classname']]['title']. ' (' . $registeredClasses[$schedulerRecord['classname']]['extension'] . ')');
					$additionalInformation = $task->getAdditionalInformation();
					if (!empty($additionalInformation)) {
						$name .= '<br />[' . htmlspecialchars($additionalInformation) . ']';
					}

						// Check if task currently has a running execution
					if (!empty($schedulerRecord['serialized_executions'])) {
						$isRunning = TRUE;
						$executionStatus = 'running';
					}

						// Prepare display of next execution date
						// If task is currently running, date is not displayed (as next hasn't been calculated yet)
						// Also hide the date if task is disabled (the information doesn't make sense, as it will not run anyway)
					if ($isRunning || $schedulerRecord['disable'] == 1) {
						$nextDate = '-';
					}
					else {
						$nextDate = date($dateFormat, $schedulerRecord['nextexecution']);
						if (empty($schedulerRecord['nextexecution'])) {
							$nextDate = $GLOBALS['LANG']->getLL('none');
						} elseif ($schedulerRecord['nextexecution'] < $GLOBALS['EXEC_TIME']) {
								// Next execution is overdue, highlight date
							$nextDate = '<span class="late" title="' . $GLOBALS['LANG']->getLL('status.legend.scheduled') . '">' . $nextDate . '</span>';
							$executionStatus = 'late';
						}
					}

						// Get execution type
					if ($task->getExecution()->getInterval() == 0 && $task->getExecution()->getCronCmd() == '') {
						$execType = $GLOBALS['LANG']->getLL('label.type.single');
						$frequency = '-';
					} else {
						$execType = $GLOBALS['LANG']->getLL('label.type.recurring');
						if ($task->getExecution()->getCronCmd() == '') {
							$frequency = $task->getExecution()->getInterval();
						} else {
							$frequency = $task->getExecution()->getCronCmd();
						}
					}

						// Get multiple executions setting
					if ($task->getExecution()->getMultiple()) {
						$multiple = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:yes');
					} else {
						$multiple = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:no');
					}

						// Define checkbox
					$startExecutionElement = '<input type="checkbox" name="tx_scheduler[execute][]" value="' . $schedulerRecord['uid'] . '" id="task_' . $schedulerRecord['uid'] . '" class="checkboxes" />';

						// Show no action links (edit, delete) if task is running
					$actions = $editAction . $deleteAction;
					if ($isRunning) {
						$actions = $stopAction;
					}

						// Check the disable status
						// Row is shown dimmed if task is disabled, unless it is still running
					if ($schedulerRecord['disable'] == 1 && !$isRunning) {
						$tableLayout[$tr] = $disabledTaskRow;
						$executionStatus  = 'disabled';
					}

						// Check if the last run failed
					$failureOutput = '';
					if (!empty($schedulerRecord['lastexecution_failure'])) {
							// Try to get the stored exception object
							/** @var $exception Exception */
						$exception = unserialize($schedulerRecord['lastexecution_failure']);
							// If the exception could not be unserialized, issue a default error message
						if ($exception === FALSE) {
							$failureDetail = $GLOBALS['LANG']->getLL('msg.executionFailureDefault');
						} else {
							$failureDetail = sprintf($GLOBALS['LANG']->getLL('msg.executionFailureReport'), $exception->getCode(), $exception->getMessage());
						}
						$failureOutput = ' <img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_failure.png') . ' alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.failure')) . '" title="' . htmlspecialchars($failureDetail) . '" />';
					}

						// Format the execution status,
						// including failure feedback, if any
					$executionStatusOutput = '<img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_' . $executionStatus . '.png') . ' id="executionstatus_' . $schedulerRecord['uid'] . '" alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.' . $executionStatus)) . '" title="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.legend.' . $executionStatus)) . '" />' . $failureOutput;

					$table[$tr][] = $startExecutionElement;
					$table[$tr][] = $actions;
					$table[$tr][] = $schedulerRecord['uid'];
					$table[$tr][] = $executionStatusOutput;
					$table[$tr][] = $name;
					$table[$tr][] = $execType;
					$table[$tr][] = $frequency;
					$table[$tr][] = $multiple;
					$table[$tr][] = $lastExecution;
					$table[$tr][] = $nextDate;

				} else {
					// The task object is not valid
					// Prepare to issue an error

						/** @var t3lib_FlashMessage $flashMessage */
					$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage',
						sprintf($GLOBALS['LANG']->getLL('msg.invalidTaskClass'), $schedulerRecord['classname']),
						'',
						t3lib_FlashMessage::ERROR
					);
					$executionStatusOutput = $flashMessage->render();

					$tableLayout[$tr] = $rowWithSpan;
					$table[$tr][] = $startExecutionElement;
					$table[$tr][] = $deleteAction;
					$table[$tr][] = $schedulerRecord['uid'];
					$table[$tr][] = $executionStatusOutput;
				}

				$tr++;
			}
				// Render table
			$content .= $this->doc->table($table, $tableLayout);

			$content .= '<input type="submit" class="button" name="go" id="scheduler_executeselected" value="' . $GLOBALS['LANG']->getLL('label.executeSelected') . '" />';
		}

		if (count($registeredClasses) > 0) {
				// Display add new task link
			$link = $GLOBALS['MCONF']['_'] . '&CMD=add';
			$content .= '<p><a href="' . htmlspecialchars($link) .'"><img '
				. t3lib_iconWorks::skinImg($this->backPath, 'gfx/new_el.gif')
				. ' alt="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_common.xml:new', TRUE)
				. '" /> ' . $GLOBALS['LANG']->getLL('action.add') . '</a></p>';
		} else {
				/** @var t3lib_FlashMessage $flashMessage */
			$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage',
				$GLOBALS['LANG']->getLL('msg.noTasksDefined'),
				'',
				t3lib_FlashMessage::INFO
			);
			$content .= $flashMessage->render();
		}

			// Display legend, if there's at least one registered task
			// Also display information about the usage of server time
		if ($numRows > 0) {
			$content .= $this->doc->spacer(20);
			$content .= '<h4>' . $GLOBALS['LANG']->getLL('status.legend') . '</h4>
			<ul>
				<li><img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_failure.png') . ' alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.failure')) . '" title="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.failure')) . '" /> ' . $GLOBALS['LANG']->getLL('status.legend.failure') . '</li>
				<li><img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_late.png') . ' alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.late')) . '" title="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.late')) . '" /> ' . $GLOBALS['LANG']->getLL('status.legend.late') . '</li>
				<li><img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_running.png') . ' alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.running')) . '" title="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.running')) . '" /> ' . $GLOBALS['LANG']->getLL('status.legend.running') . '</li>
				<li><img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_scheduled.png') . ' alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.scheduled')) . '" title="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.scheduled')) . '" /> ' . $GLOBALS['LANG']->getLL('status.legend.scheduled') . '</li>
				<li><img ' . t3lib_iconWorks::skinImg(t3lib_extMgm::extRelPath('scheduler'), 'res/gfx/status_disabled.png') . ' alt="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.disabled')) . '" title="' . htmlspecialchars($GLOBALS['LANG']->getLL('status.disabled')) . '" /> ' . $GLOBALS['LANG']->getLL('status.legend.disabled') . '</li>
			</ul>';
			$content .= $this->doc->spacer(10);
			$content .= $this->displayServerTime();
		}


		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $content;
	}




	

	
	/*************************
	 *
	 * RENDERING UTILITIES
	 *
	 *************************/

	/**
	 * Gets the filled markers that are used in the HTML template.
	 *
	 * @return array The filled marker array
	 */
	protected function getTemplateMarkers() {
		$markers = array(
			'CSH' => t3lib_BEfunc::wrapInHelp('_MOD_tools_txschedulertimelineM1', ''),
			'FUNC_MENU' => $this->getFunctionMenu(),
			'CONTENT'   => $this->content,
			'TITLE'     => $GLOBALS['LANG']->getLL('title'),
		);

		return $markers;
	}

	/**
	 * Gets the function menu selector for this backend module.
	 *
	 * @return string The HTML representation of the function menu selector
	 */
	protected function getFunctionMenu() {
		$functionMenu = t3lib_BEfunc::getFuncMenu(
			0,
			'SET[function]',
			$this->MOD_SETTINGS['function'],
			$this->MOD_MENU['function']
		);

		return $functionMenu;
	}

	/**
	 * Gets the buttons that shall be rendered in the docHeader.
	 *
	 * @return array Available buttons for the docHeader
	 */
	protected function getDocHeaderButtons() {
		$buttons = array(
			'reload'   => '',
			'shortcut' => $this->getShortcutButton(),
		);

		if (empty($this->CMD) || $this->CMD == 'list') {
			$buttons['reload'] = '<a href="' . $GLOBALS['MCONF']['_'] . '" title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.reload', TRUE) . '">' .
			  t3lib_iconWorks::getSpriteIcon('actions-system-refresh') .
		  '</a>';
		}

		return $buttons;
	}

	/**
	 * Gets the button to set a new shortcut in the backend (if current user is allowed to).
	 *
	 * @return string HTML representation of the shortcut button
	 */
	protected function getShortcutButton() {
		$result = '';
		if ($GLOBALS['BE_USER']->mayMakeShortcut()) {
			$result = $this->doc->makeShortcutIcon('', 'function', $this->MCONF['name']);
		}

		return $result;
	}
}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler/class.tx_scheduler_module.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler/class.tx_scheduler_module.php']);
}
?>
