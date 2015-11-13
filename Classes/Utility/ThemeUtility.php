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

	/**
	 * @var array $extensionConfiguration
	 */
	protected static $extensionConfiguration;

	/**
	 * Returns TRUE if on current page the given theme is found.
	 *
	 * @param string $theme
	 * @return boolean
	 */
	public static function isTheme($theme) {
		$themesDirectory = self::getThemesDirectory();
		// Relative path of theme is the theme name to search for.
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

	/**
	 * Returns TRUE if on any page in the rootline the given theme is found.
	 *
	 * @param string $theme
	 * @return boolean
	 */
	public static function isThemeInRootline($theme) {
		$themesDirectory = self::getThemesDirectory();
		// Relative path of theme is the theme name to search for.
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

	/**
	 * @return integer
	 */
	public static function getPageId() {
		return (TYPO3_MODE === 'BE')
			? (integer) GeneralUtility::_GP('id')
			: $GLOBALS['TSFE']->id;
	}

	/**
	 * @return array
	 */
	public static function getRootline() {
		return (TYPO3_MODE === 'BE')
			? BackendUtility::BEgetRootLine(self::getPageId(), '', TRUE)
			: $GLOBALS['TSFE']->tmpl->rootLine;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public static function getExtensionConfiguration($key = '') {
		if (self::$extensionConfiguration === NULL) {
			self::$extensionConfiguration = isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager'])
				? (array) unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager'])
				: array();
		}
		if ($key) {
			return isset(self::$extensionConfiguration[$key])
				? self::$extensionConfiguration[$key]
				: NULL;
		}
		return self::$extensionConfiguration;
	}

	/**
	 * @param boolean $absolute
	 * @return string
	 */
	public static function getThemesDirectory($absolute = FALSE) {
		$themesDirectory = self::getExtensionConfiguration('themesDirectory');
		$themesDirectory = ($themesDirectory)
			? trim($themesDirectory, '/') . '/'
			: 'fileadmin/themes/';
		return ($absolute)
			? GeneralUtility::getFileAbsFileName($themesDirectory)
			: $themesDirectory;
	}

}

?>