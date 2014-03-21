<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
$themesDirectory = $extensionConfiguration['themesDirectory']
	? rtrim($extensionConfiguration['themesDirectory'], '/') . '/'
	: 'fileadmin/themes/';

// Include ext_localconf.php of themes.
$themesPath = t3lib_div::getFileAbsFileName($themesDirectory);
if ($themesPath) {

	$themes = t3lib_div::get_dirs($themesPath);
	$extensionPath = t3lib_extMgm::extPath($_EXTKEY);

	foreach ($themes as $themeName) {
		require_once($themesPath . $themeName . '/ext_localconf.php');
		// Include ext_tables.sql.
		if (is_file($themesPath . $themeName . '/ext_tables.sql')) {
			$GLOBALS['TYPO3_LOADED_EXT'][$_EXTKEY . $themeName]['ext_tables.sql'] = $pathAndFilename;
		}
	}

	// Append theme path to allowed paths.
	$GLOBALS['TYPO3_CONF_VARS']['FE']['addAllowedPaths'] .= ($GLOBALS['TYPO3_CONF_VARS']['FE']['addAllowedPaths'] ? ',' : '') . $extensionConfiguration['theme'];
}

// Initialize TypoScript template hook.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSourcesAtEnd'][] = 'EXT:adx_theme_manager/Classes/Hooks/TsTemplate.php:&Tx_AdxThemeManager_Hooks_TsTemplate->includeStaticTypoScriptSources';

?>