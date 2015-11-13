<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$themesDirectory = \AdGrafik\AdxThemeManager\Utility\ThemeUtility::getThemesDirectory();

// Include TypoScript configuration.
$pathAndFilename = substr(dirname(__FILE__), strpos(dirname(__FILE__), $themesDirectory)) . '/Configuration/TSconfig/User.ts';
if (is_file(PATH_site . $pathAndFilename)) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:' . $pathAndFilename . '">');
}

$pathAndFilename = substr(dirname(__FILE__), strpos(dirname(__FILE__), $themesDirectory)) . '/Configuration/TSconfig/Page.ts';
if (is_file(PATH_site . $pathAndFilename)) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:' . $pathAndFilename . '">');
}

?>