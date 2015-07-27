<?php
defined('TYPO3_MODE') or die();

$tempColumns = array(
	'tx_adxthememanager_static_files' => array(
		'label' => 'LLL:EXT:adx_theme_manager/Resources/Private/Language/Locallang.xml:tx_adxthememanager_static_files',
		'exclude' => 1,
		'config' => array(
			'type' => 'select',
			'size' => 3,
			'maxitems' => 100,
			'items' => array(),
			'itemsProcFunc' => 'AdGrafik\\AdxThemeManager\\ItemProcessFunction\\SysTemplate->getStaticFiles',
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_template', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_template', 'tx_adxthememanager_static_files', '', 'after:include_static_file');

?>