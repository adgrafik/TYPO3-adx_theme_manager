<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "adx_theme_manager".
 *
 * Auto generated 19-05-2014 15:05
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'ad: Theme Manager',
	'description' => 'Adds an option to the template record to load static TypoScript templates per file and more.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.2',
	'dependencies' => 'cms,version',
	'conflicts' => '',
	'priority' => 'bottom',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'fileadmin/themes/',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Arno Dudek',
	'author_email' => 'webmaster@adgrafik.at',
	'author_company' => 'ad:grafik',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'cms' => '',
			'version' => '',
			'php' => '5.3.3-0.0.0',
			'typo3' => '4.5.0-6.1.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'_md5_values_when_last_written' => '',
	'suggests' => 
	array (
	),
);

?>