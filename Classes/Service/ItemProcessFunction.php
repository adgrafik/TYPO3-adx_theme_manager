<?php

class Tx_AdxThemeManager_Service_ItemProcessFunction {

	/**
	 * Fill TCA form selection "Skin Path" with all skins.
	 *
	 * @param array $params
	 * @param t3lib_TCEforms $parentObject
	 * @return void
	 */
	public function getAvailableThemes(&$params, $parentObject) {

		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
		$themesDirectory = $extensionConfiguration['themesDirectory'];

		$themes = (array) t3lib_div::get_dirs(t3lib_div::getFileAbsFileName($themesDirectory));
		foreach ($themes as $theme) {
			$localconfPathAndFileName = t3lib_div::getFileAbsFileName($themesDirectory . $theme . '/ext_localconf.php');
			if (is_file($localconfPathAndFileName)) {
				$params['items'][] = array($theme, $themesDirectory . $theme . '/');
			}
		}
	}
}

?>