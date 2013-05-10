<?php

class Tx_AdxThemeManager_Hooks_TsTemplate {

	/**
	 * Includes static template records (from static_template table) and static template files (from extensions) for the input template record row.
	 *
	 * @param array $params							Array of parameters from the parent class. Includes idList, templateId, pid, and row.
	 * @param t3lib_TStemplate $parentObject		Reference back to parent object, t3lib_tstemplate or one of its subclasses.
	 * @return void
	 */
	public function includeStaticTypoScriptSources(array $params, t3lib_TStemplate $parentObject) {

		if ($params['row']['tx_adxthememanager_static_files']) {

			$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
			$dynamicTypoScriptPath = $extensionConfiguration['dynamicTypoScriptPath']
				? rtrim($extensionConfiguration['dynamicTypoScriptPath'], '.') . '.'
				: 'plugin.tx_adxthememanager.';

			$themeDirectories = t3lib_div::trimExplode(',', $params['row']['tx_adxthememanager_static_files'], TRUE);
			foreach ($themeDirectories as $themeDirectory) {

				$themePath = t3lib_div::getFileAbsFileName($themeDirectory);
				$themeName = basename($themeDirectory);

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

				// Append theme path.
				$templateRecord['constants'] .= LF . '/**' . LF;
				$templateRecord['constants'] .= ' * included by adx_theme_manager' . LF;
				$templateRecord['constants'] .= ' * Theme path: ' . $themeDirectory . LF;
				$templateRecord['constants'] .= ' */' . LF;
				$templateRecord['constants'] .= $dynamicTypoScriptPath . 'path.' . lcfirst($themeName) . ' = ' . $themeDirectory . LF;
				$templateRecord['constants'] .= $dynamicTypoScriptPath . 'path.current = ' . $themeDirectory . LF;

				$themePathAndFilenames = t3lib_div::getAllFilesAndFoldersInPath(array(), $themePath, 'ts,txt');
				sort($themePathAndFilenames);

				foreach ($themePathAndFilenames as $key => $themePathAndFilename) {

					if (preg_match('{((/static/constants\.(txt|ts)|/static/setup\.(txt|ts))|(/typoscript.*\.(txt|ts)))$}i', $themePathAndFilename)) {

						$source = t3lib_div::getUrl($themePathAndFilename);
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