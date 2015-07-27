<?php
namespace AdGrafik\AdxThemeManager\ItemProcessFunction;

class SysTemplate {

	/**
	 * Fill TCA form selection "Skin Path" with all skins.
	 *
	 * @param array $params
	 * @param t3lib_TCEforms $parentObject
	 * @return void
	 */
	public function getStaticFiles(&$params, $parentObject) {

		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_theme_manager']);
		$themesDirectory = $extensionConfiguration['themesDirectory']
			? rtrim($extensionConfiguration['themesDirectory'], '/') . '/'
			: 'fileadmin/themes/';

		$themes = (array) \TYPO3\CMS\Core\Utility\GeneralUtility::get_dirs(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($themesDirectory));
		foreach ($themes as $theme) {
			$localconfPathAndFileName = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($themesDirectory . $theme . '/ext_localconf.php');
			if (is_file($localconfPathAndFileName)) {
				$params['items'][] = array($theme, $themesDirectory . $theme . '/');
			}
		}
	}
}

?>