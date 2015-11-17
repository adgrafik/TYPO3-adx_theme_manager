<?php
namespace AdGrafik\AdxThemeManager;

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

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class ext_update {

	/**
	 * Array of flash messages (params) array[][status,title,message]
	 *
	 * @var array
	 */
	protected $messageArray = array();

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Main update function called by the extension manager.
	 *
	 * @return string
	 */
	public function main() {
		// Updates from 1.0.2.
		$this->updateFrom_1_0_2();
		return $this->generateOutput();
	}

	/**
	 * Called by the extension manager to determine if the update menu entry
	 * should by showed.
	 *
	 * @return bool
	 */
	public function access() {
		// Need update from 1.0.2 if theme field contains directories.
		$access = (boolean) $this->databaseConnection->exec_SELECTcountRows('*', 'sys_template', 'tx_adxthememanager_static_files LIKE "%/%"');
		return $access;
	}

	/**
	 * This update changes the theme value from relative directory to the name of the directory only.
	 *
	 * @return void
	 */
	protected function updateFrom_1_0_2() {
		$result = (array) $this->databaseConnection->exec_SELECTgetRows('uid, tx_adxthememanager_static_files', 'sys_template', 'tx_adxthememanager_static_files LIKE "%/%"');
		// Nothing else to do if no record found.
		if (count($result) === 0) {
			return;
		}
		foreach ($result as $row) {
			$themeDirectories = (array) GeneralUtility::trimExplode(',', $row['tx_adxthememanager_static_files'], TRUE);
			foreach ($themeDirectories as &$themeDirectory) {
				$themeDirectory = trim(basename($themeDirectory), '/');
			}
			$update = array(
				'tx_adxthememanager_static_files' => implode(',', $themeDirectories)
			);
			$this->databaseConnection->exec_UPDATEquery('sys_template', 'uid=' . $row['uid'], $update);
		}
		$this->messageArray[] = array(FlashMessage::OK, 'Updated from v1.0.2', count($result) . ' records from sys_template have been updated!');
	}

	/**
	 * Generates output by using flash messages.
	 *
	 * @return string
	 */
	protected function generateOutput() {
		$output = '';
		foreach ($this->messageArray as $messageItem) {
			/** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
			$flashMessage = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
				$messageItem[2],
				$messageItem[1],
				$messageItem[0]);
			$output .= $flashMessage->render();
		}
		return $output;
	}

}
