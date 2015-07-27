<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
$themesDirectory = $extensionConfiguration['themesDirectory']
	? rtrim($extensionConfiguration['themesDirectory'], '/') . '/'
	: 'fileadmin/themes/';

// Append theme path to allowed paths.
$GLOBALS['TYPO3_CONF_VARS']['FE']['addAllowedPaths'] .= ($GLOBALS['TYPO3_CONF_VARS']['FE']['addAllowedPaths'] ? ',' : '') . $themesDirectory;

// Include ext_localconf.php of themes.
$themesPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($themesDirectory);
if ($themesPath) {

	$themes = \TYPO3\CMS\Core\Utility\GeneralUtility::get_dirs($themesPath);
	$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);

	foreach ($themes as $themeName) {
		require_once($themesPath . $themeName . '/ext_localconf.php');
		// Include ext_tables.sql.
		if (is_file($themesPath . $themeName . '/ext_tables.sql')) {
			$GLOBALS['TYPO3_LOADED_EXT'][$_EXTKEY . $themeName]['ext_tables.sql'] = $themesPath . $themeName . '/ext_tables.sql';
		}
	}
}

// Initialize hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSourcesAtEnd'][] = 'EXT:adx_theme_manager/Classes/Hooks/TemplateService.php:&AdGrafik\\AdxThemeManager\\Hooks\\TemplateService->includeStaticTypoScriptSources';

?>
