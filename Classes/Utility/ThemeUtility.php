<?php
namespace AdGrafik\AdxThemeManager\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Arno Dudek <webmaster@adgrafik.at>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class ThemeUtility {

	public static function isTheme($theme) {
		// Relative path of theme is the theme name to search for.
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
		$themesDirectory = trim($extensionConfiguration['themesDirectory'], '/') . '/';
		$theme = trim($themesDirectory . $theme, '/') . '/';
		// Search current page for theme.
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('tx_adxthememanager_static_files', 'sys_template', 'pid=' . self::getPageId() . ' AND NOT deleted AND NOT hidden');
		// If templates found look for included themes.
		foreach ($result as $themeRow) {
			if (GeneralUtility::inList($themeRow['tx_adxthememanager_static_files'], $theme)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public static function isThemeInRootline($theme) {
		// Relative path of theme is the theme name to search for.
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
		$themesDirectory = trim($extensionConfiguration['themesDirectory'], '/') . '/';
		$theme = trim($themesDirectory . $theme, '/') . '/';
		// Search the rootline up for theme.
		foreach (self::getRootline() as $row) {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('tx_adxthememanager_static_files', 'sys_template', 'pid=' . $row['uid'] . ' AND NOT deleted AND NOT hidden');
			// If templates found look for included themes.
			foreach ($result as $themeRow) {
				if (GeneralUtility::inList($themeRow['tx_adxthememanager_static_files'], $theme)) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	public static function getPageId() {
		return (TYPO3_MODE === 'BE')
			? (integer) GeneralUtility::_GP('id')
			: $GLOBALS['TSFE']->id;
	}

	public static function getRootline() {
		return (TYPO3_MODE === 'BE')
			? BackendUtility::BEgetRootLine(self::getPageId(), '', TRUE)
			: $GLOBALS['TSFE']->tmpl->rootLine;
	}

}

?>