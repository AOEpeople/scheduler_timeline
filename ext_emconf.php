<?php

########################################################################
# Extension Manager/Repository config file for ext "scheduler_timeline".
#
# Auto generated 08-08-2011 20:24
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
	'version' => '0.1.1',
	'_md5_values_when_last_written' => 'a:34:{s:25:"class.ux_tx_scheduler.php";s:4:"5e79";s:21:"ext_conf_template.txt";s:4:"dc51";s:12:"ext_icon.gif";s:4:"b5e1";s:17:"ext_localconf.php";s:4:"2ea2";s:14:"ext_tables.php";s:4:"a7b5";s:14:"ext_tables.sql";s:4:"f3aa";s:24:"ext_typoscript_setup.txt";s:4:"21e1";s:48:"interface.tx_schedulertimeline_returnmessage.php";s:4:"8942";s:7:"tca.php";s:4:"5ed3";s:41:"Classes/Controller/TimelineController.php";s:4:"8f9d";s:28:"Classes/Domain/Model/Log.php";s:4:"5b18";s:29:"Classes/Domain/Model/Task.php";s:4:"96d2";s:43:"Classes/Domain/Repository/LogRepository.php";s:4:"4840";s:44:"Classes/Domain/Repository/TaskRepository.php";s:4:"10ba";s:35:"Classes/Scheduler/ExceptionTask.php";s:4:"238d";s:42:"Classes/ViewHelpers/DurationViewHelper.php";s:4:"508f";s:39:"Classes/ViewHelpers/GanttViewHelper.php";s:4:"36d1";s:40:"Classes/ViewHelpers/StatusViewHelper.php";s:4:"ceb9";s:46:"Classes/ViewHelpers/For/IncreaseViewHelper.php";s:4:"e712";s:53:"Classes/ViewHelpers/Format/SpeakingDateViewHelper.php";s:4:"0166";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"eec2";s:37:"Resources/Private/Layouts/module.html";s:4:"39b9";s:47:"Resources/Private/Partials/Timeline/Detail.html";s:4:"221d";s:50:"Resources/Private/Templates/Timeline/timeline.html";s:4:"b1ca";s:44:"Resources/Public/Images/bg_notifications.gif";s:4:"df73";s:36:"Resources/Public/Images/gradient.png";s:4:"17f6";s:32:"Resources/Public/Images/hour.gif";s:4:"91a6";s:38:"Resources/Public/Images/moduleicon.gif";s:4:"b5e1";s:37:"Resources/Public/JavaScript/common.js";s:4:"af6d";s:47:"Resources/Public/JavaScript/jquery-1.6.2.min.js";s:4:"a1a8";s:46:"Resources/Public/JavaScript/tooltip.dynamic.js";s:4:"c673";s:38:"Resources/Public/JavaScript/tooltip.js";s:4:"d731";s:36:"Resources/Public/StyleSheet/bars.css";s:4:"c98d";s:40:"Resources/Public/StyleSheet/timeline.css";s:4:"4151";}',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '6.2.0-0.0.0',
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