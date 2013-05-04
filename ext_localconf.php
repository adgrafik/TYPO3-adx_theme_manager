<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['adx_theme_manager']);

// Include ext_localconf.php of themes.
$themesPath = t3lib_div::getFileAbsFileName($extensionConfiguration['themesDirectory']);
if ($themesPath) {

	$themes = t3lib_div::get_dirs($themesPath);
	$extensionPath = t3lib_extMgm::extPath($_EXTKEY);

	foreach ($themes as $theme) {
		require_once($themesPath . $theme . '/ext_localconf.php');
	}

	// Append theme path to allowed paths.
	$TYPO3_CONF_VARS['FE']['addAllowedPaths'] .= ($TYPO3_CONF_VARS['FE']['addAllowedPaths'] ? ',' : '') . $extensionConfiguration['theme'];
}

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSourcesAtEnd'][] = 'EXT:adx_theme_manager/Classes/Service/Utility.php:&Tx_AdxThemeManager_Service_Utility->includeTypoScriptForFrameworkCommonAndSkins';

?>