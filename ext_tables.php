<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);

// Include ext_tables.php of themes.
$themesPath = t3lib_div::getFileAbsFileName($extensionConfiguration['themesDirectory']);
if (!$themesPath) {
	trigger_error('Theme path was not set in the configuration of the extension "adx_theme_manager".', E_USER_WARNING);
} else {

	if (!is_dir($themesPath)) {
		trigger_error('Theme path "' . $themesPath . '" of the extension "adx_theme_manager" dose not exists and will be created.', E_USER_WARNING);
		t3lib_div::mkdir($themesPath);
	}

	$themes = t3lib_div::get_dirs($themesPath);

	// Create default theme if no theme exists.
	if (!count($themes)) {
		$cmd = 'cp -R ' . escapeshellarg(t3lib_extMgm::extPath('adx_theme_manager') . 'Resources/Private/DefaultTheme') . ' ' . escapeshellarg($themesPath . 'Common');
		t3lib_utility_Command::exec($cmd);
		$themes[] = 'Common';
	}

	foreach ($themes as $theme) {
		$tablesPathAndFilename = $themesPath . $theme . '/ext_tables.php';
		if (is_file($tablesPathAndFilename)) {
			require_once($tablesPathAndFilename);
		}
	}
}

/**
 * Add-ons for sys_template
 */
$tempColumns = array(
	'tx_adxthememanager_static_files' => array(
		'label' => 'LLL:EXT:adx_theme_manager/Resources/Private/Language/Locallang.xml:tx_adxthememanager_static_files',
		'exclude' => 1,
		'config' => array(
			'type' => 'select',
			'size' => 3,
			'maxitems' => 100,
			'items' => array(),
			'itemsProcFunc' => 'Tx_AdxThemeManager_Service_ItemProcessFunction->getAvailableThemes',
		),
	),
);

t3lib_div::loadTCA('sys_template');
t3lib_extMgm::addTCAcolumns('sys_template', $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('sys_template', 'tx_adxthememanager_static_files', '', 'after:include_static_file');

?>