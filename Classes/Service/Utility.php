<?php

class Tx_AdxThemeManager_Service_Utility {

	/**
	 * Includes static template records (from static_template table) and static template files (from extensions) for the input template record row.
	 *
	 * @param array $params							Array of parameters from the parent class. Includes idList, templateId, pid, and row.
	 * @param t3lib_TStemplate $parentObject		Reference back to parent object, t3lib_tstemplate or one of its subclasses.
	 * @return void
	 */
	public function includeTypoScriptForFrameworkCommonAndSkins(array $params, t3lib_TStemplate $parentObject) {

		if ($params['row']['tx_adxthememanager_static_files']) {

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
				$templateRecord['constants'] .= LF . '/**' . LF . ' * Theme: ' . $themeDirectory . LF . ' */';
				$templateRecord['constants'] .= LF . 'plugin.tx_adxthememanager.themeDirectory.' . lcfirst($themeName) . ' = ' . $themeDirectory;
				$templateRecord['constants'] .= LF . 'plugin.tx_adxthememanager.themeDirectory.current = ' . $themeDirectory;

				$themePathAndFilenames = t3lib_div::getAllFilesAndFoldersInPath(array(), $themePath, 'ts,txt');
				sort($themePathAndFilenames);

				foreach ($themePathAndFilenames as $key => $themePathAndFilename) {
					if (strpos($themePathAndFilename, '/TypoScript/') !== FALSE) {
						if (stripos($themePathAndFilename, '/constants') !== FALSE) {
							$templateRecord['constants'] .= LF . '/**' . LF . ' * ' . $themePathAndFilename . LF . ' */' . LF;
							$templateRecord['constants'] .= t3lib_div::getUrl($themePathAndFilename);
						} else if (stripos($themePathAndFilename, '/setup') !== FALSE) {
							$templateRecord['config'] .= LF . '/**' . LF . ' * ' . $themePathAndFilename . LF . ' */' . LF;
							$templateRecord['config'] .= t3lib_div::getUrl($themePathAndFilename);
						}
					}
				}

				$parentObject->processTemplate($templateRecord, $params['idList'] . ',' . $templateId, $params['pid'], $templateId, $params['templateId']);
			}
		}
	}

}

?>