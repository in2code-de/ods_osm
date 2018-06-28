<?php
if (!defined('TYPO3_MODE')) die('Access denied.');

/* --------------------------------------------------
	Extend existing tables
-------------------------------------------------- */

$tempColumns = array (
	'tx_odsosm_marker' => array (        
		'exclude' => 1,        
		'label' => 'LLL:EXT:ods_osm/locallang_db.xml:tt_address_group.tx_odsosm_marker',        
		'config' => array (
			'type' => 'group',    
			'internal_type' => 'db',    
			'allowed' => 'tx_odsosm_marker',    
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_groups',$tempColumns,1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_groups','tx_odsosm_marker;;;;1-1-1');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category',$tempColumns,1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category','tx_odsosm_marker;;;;1-1-1');

/* --------------------------------------------------
	New tables
-------------------------------------------------- */

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_odsosm_marker');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_odsosm_track');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_odsosm_track');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_odsosm_vector');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_odsosm_vector');

/* --------------------------------------------------
	Plugin
-------------------------------------------------- */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:ods_osm/locallang_db.xml:tt_content.list_type_pi1',
		$_EXTKEY . '_pi1',
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif'
	),
	'list_type'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY . '/pi1/flexform.xml');
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] ='pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';

if (TYPO3_MODE == 'BE') {

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::insertModuleFunction(
		'web_func',
		'tx_odsosm_geocodeWizard',
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'func_wizards/class.tx_odsosm_geocodeWizard.php',
		'LLL:EXT:ods_osm/locallang.xml:wiz_geocode'
	);
}

if (TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version()) < 8000000) {
	// TYPO3 6.2 compatibility
	// Register colorpicker wizard
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModulePath(
		'wizard_coordinatepicker',
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'wizard/'
	);
}

/**
 * Register icons
 */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ods_osm',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:ods_osm/Resources/Public/Icons/osm.png']
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ods_osm/Configuration/TSConfig/ContentElementWizard.typoscript">');
?>
