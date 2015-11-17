<?php
namespace AdGrafik\AdxThemeManager\Hooks;

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

use TYPO3\CMS\Core\TypoScript\ExtendedTemplateService;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use AdGrafik\AdxThemeManager\Utility\ThemeUtility;

class TemplateServiceHook extends ExtendedTemplateService {

	/**
	 * Includes static template records (from static_template table) and static template files (from extensions) for the input template record row.
	 *
	 * @param array $params Array of parameters from the parent class. Includes idList, templateId, pid, and row.
	 * @param \TYPO3\CMS\Core\TypoScript\ExtendedTemplateService $parentObject Reference back to parent object, or one of its subclasses.
	 * @return void
	 */
	public function includeStaticTypoScriptSources(array $params, TemplateService $parentObject) {

		if ($params['row']['tx_adxthememanager_static_files']) {
			$themeNames = GeneralUtility::trimExplode(',', $params['row']['tx_adxthememanager_static_files'], TRUE);
		}

		if (count($themeNames)) {

			$themesPath = ThemeUtility::getThemesPath();
			$absoluteThemesPath = ThemeUtility::getThemesPath(TRUE);

			foreach ($themeNames as $themeName) {

				$absoluteThemePath = $absoluteThemesPath . $themeName . '/';
				$themePath = $themesPath . $themeName . '/';

				$templateId = 'ext_adxthememanager_' . strtolower($themeName);
				$templateRecord = array(
					'constants' => '',
					'config' => '',
					'editorcfg' => '',
					'include_static' => '',
					'include_static_file' => '',
					'title' => 'Theme: ' . $themeName,
					'uid' => 'EXT:adx_theme_manager:' . $themeName,
				);
				$themeTypoScriptName = GeneralUtility::underscoredToLowerCamelCase($themeName);
				$themeTypoScriptName = str_replace(array('.', '-'), '_', $themeTypoScriptName);

				// Append theme path.
				$templateRecord['constants'] .= LF . '/**' . LF;
				$templateRecord['constants'] .= ' * included by adx_theme_manager' . LF;
				$templateRecord['constants'] .= ' * Theme path: ' . $themePath . LF;
				$templateRecord['constants'] .= ' */' . LF;
				$templateRecord['constants'] .= 'plugin.tx_adxthememanager.path.' . $themeTypoScriptName . ' = ' . $themePath . LF;
				$templateRecord['constants'] .= 'plugin.tx_adxthememanager.path.current = ' . $themePath . LF;

				$themePathAndFilenames = GeneralUtility::getAllFilesAndFoldersInPath(array(), $absoluteThemePath, 'ts,txt');
				sort($themePathAndFilenames);

				foreach ($themePathAndFilenames as $key => $themePathAndFilename) {

					if (preg_match('{((/static/constants\.(txt|ts)|/static/setup\.(txt|ts))|(/typoscript.*\.(txt|ts)))$}i', $themePathAndFilename)) {

						$source = GeneralUtility::getUrl($themePathAndFilename);
						if (trim($source)) {

							$head = LF . LF . '/**' . LF;
							$head .= ' * included by adx_theme_manager' . LF;
							$head .= ' * Path: ' . str_replace(PATH_site, '', $themePathAndFilename) . LF;
							$head .= ' */' . LF;

							if (stripos($themePathAndFilename, '/constants') !== FALSE) {
								$templateRecord['constants'] .= $head . $source;
							} else if (stripos($themePathAndFilename, '/setup') !== FALSE) {
								$templateRecord['config'] .= $head . $source;
							}
						}
					}
				}

				$parentObject->processTemplate($templateRecord, $params['idList'] . ',' . $templateId, $params['pid'], $templateId, $params['templateId']);
			}
		}
	}

}

?>