<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$themesDirectory = \AdGrafik\AdxThemeManager\Utility\ThemeUtility::getThemesDirectory();

if ($themesDirectory) {
	$absoluteThemesPath = \AdGrafik\AdxThemeManager\Utility\ThemeUtility::getThemesDirectory(TRUE);
	if (is_dir($absoluteThemesPath) === FALSE) {
		\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($absoluteThemesPath);
	}
	if ($absoluteThemesPath) {
		$themes = \TYPO3\CMS\Core\Utility\GeneralUtility::get_dirs($absoluteThemesPath);
		// Create default theme if no theme exists.
		if (count($themes) === FALSE) {
			$cmd = 'cp -R ' . escapeshellarg(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_theme_manager') . 'Resources/Private/DefaultTheme') . ' ' . escapeshellarg($absoluteThemesPath . 'common');
			\TYPO3\CMS\Core\Utility\CommandUtility::exec($cmd);
			$themes[] = 'common';
		}
		if (count($themes)) {
			// Include ext_localconf.php and ext_tables.sql of themes.
			foreach ($themes as $themeName) {
				$themeName = trim($themeName, '/');
				require_once($absoluteThemesPath . $themeName . '/ext_localconf.php');
				// Include ext_tables.sql.
				if (is_file($absoluteThemesPath . $themeName . '/ext_tables.sql')) {
					$GLOBALS['TYPO3_LOADED_EXT'][$_EXTKEY . $themeName]['ext_tables.sql'] = $absoluteThemesPath . $themeName . '/ext_tables.sql';
				}
			}
			// Append theme path to allowed paths.
			$GLOBALS['TYPO3_CONF_VARS']['FE']['addAllowedPaths'] .= ($GLOBALS['TYPO3_CONF_VARS']['FE']['addAllowedPaths'] ? ',' : '') . $themesDirectory;
			// Initialize hook only if themes found.
			$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSourcesAtEnd'][] = 'AdGrafik\\AdxThemeManager\\Hooks\\TemplateServiceHook->includeStaticTypoScriptSources';
		}
	}
}

?>