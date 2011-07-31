<?php

########################################################################
# Extension Manager/Repository config file for ext "scheduler_timeline".
#
# Auto generated 31-07-2011 15:12
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Scheduler Timeline',
	'description' => 'Logs information about scheduler tasks and displays them in a timeline',
	'category' => 'misc',
	'author' => 'Fabrizio Branca',
	'author_email' => 'typo3@fabrizio-branca.de',
	'shy' => '',
	'dependencies' => 'extbase,fluid,scheduler',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'AOE media GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.0.1',
	'_md5_values_when_last_written' => 'a:25:{s:37:"class.tx_schedulertimeline_module.php";s:4:"867c";s:25:"class.ux_tx_scheduler.php";s:4:"22ba";s:21:"ext_conf_template.txt";s:4:"c5a1";s:12:"ext_icon.gif";s:4:"b5e1";s:17:"ext_localconf.php";s:4:"2ea2";s:14:"ext_tables.php";s:4:"66f2";s:14:"ext_tables.sql";s:4:"1442";s:48:"interface.tx_schedulertimeline_returnmessage.php";s:4:"8942";s:7:"tca.php";s:4:"5ed3";s:41:"Classes/Controller/TimelineController.php";s:4:"836f";s:28:"Classes/Domain/Model/Log.php";s:4:"3af7";s:29:"Classes/Domain/Model/Task.php";s:4:"c3e0";s:43:"Classes/Domain/Repository/LogRepository.php";s:4:"a7ad";s:44:"Classes/Domain/Repository/TaskRepository.php";s:4:"91b7";s:35:"Classes/Scheduler/ExceptionTask.php";s:4:"1b53";s:42:"Classes/ViewHelpers/DurationViewHelper.php";s:4:"508f";s:39:"Classes/ViewHelpers/GanttViewHelper.php";s:4:"432c";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"eec2";s:37:"Resources/Private/Layouts/module.html";s:4:"39b9";s:50:"Resources/Private/Templates/Timeline/timeline.html";s:4:"c6b1";s:32:"Resources/Public/Images/hour.gif";s:4:"91a6";s:38:"Resources/Public/Images/moduleicon.gif";s:4:"b5e1";s:37:"Resources/Public/JavaScript/common.js";s:4:"f693";s:47:"Resources/Public/JavaScript/jquery-1.6.2.min.js";s:4:"a1a8";s:38:"Resources/Public/StyleSheet/module.css";s:4:"ba39";}',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.5.0-0.0.0',
			'extbase' => '',
			'fluid' => '',
			'scheduler' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>