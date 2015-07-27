<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
$themesDirectory = $extensionConfiguration['themesDirectory']
	? rtrim($extensionConfiguration['themesDirectory'], '/') . '/'
	: 'fileadmin/themes/';

// Include ext_tables.php of themes.
$themesPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($themesDirectory);
if (!$themesPath) {
	trigger_error('Theme path was not set in the configuration of the extension "adx_theme_manager".', E_USER_WARNING);
} else {

	if (!is_dir($themesPath)) {
		trigger_error('Theme path "' . $themesPath . '" of the extension "adx_theme_manager" dose not exists and will be created.', E_USER_WARNING);
		\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($themesPath);
	}

	$themes = \TYPO3\CMS\Core\Utility\GeneralUtility::get_dirs($themesPath);

	// Create default theme if no theme exists.
	if (!count($themes)) {
		$cmd = 'cp -R ' . escapeshellarg(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_theme_manager') . 'Resources/Private/DefaultTheme') . ' ' . escapeshellarg($themesPath . 'Common');
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

?>