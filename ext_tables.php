<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$themesDirectory = \AdGrafik\AdxThemeManager\Utility\ThemeUtility::getThemesDirectory();

if ($themesDirectory) {
	$absoluteThemesPath = \AdGrafik\AdxThemeManager\Utility\ThemeUtility::getThemesDirectory(TRUE);
	if ($absoluteThemesPath) {
		$themes = \TYPO3\CMS\Core\Utility\GeneralUtility::get_dirs($absoluteThemesPath);
		// Include ext_tables.php of themes.
		foreach ($themes as $theme) {
			$tablesPathAndFilename = $absoluteThemesPath . $theme . '/ext_tables.php';
			if (is_file($tablesPathAndFilename)) {
				require_once($tablesPathAndFilename);
			}
		}
	}
}

?>