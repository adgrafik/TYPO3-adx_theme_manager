<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['adx_theme_manager']);
$themesDirectory = $extensionConfiguration['themesDirectory'];

// Include TypoScript configuration.
$pathAndFilename = substr(dirname(__FILE__), strpos(dirname(__FILE__), $themesDirectory)) . '/Configuration/TSconfig/User.ts';
if (is_file(PATH_site . $pathAndFilename)) {
	t3lib_extMgm::addUserTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:' . $pathAndFilename . '">');
}

$pathAndFilename = substr(dirname(__FILE__), strpos(dirname(__FILE__), $themesDirectory)) . '/Configuration/TSconfig/Page.ts';
if (is_file(PATH_site . $pathAndFilename)) {
	t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:' . $pathAndFilename . '">');
}

?>