<?php
if (!defined('TYPO3_MODE')) die('Access denied.');

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Core\Utility\VersionNumberUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;


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

ExtensionManagementUtility::addTCAcolumns('fe_groups',$tempColumns,1);
ExtensionManagementUtility::addToAllTCAtypes('fe_groups','tx_odsosm_marker;;;;1-1-1');

ExtensionManagementUtility::addTCAcolumns('sys_category',$tempColumns,1);
ExtensionManagementUtility::addToAllTCAtypes('sys_category','tx_odsosm_marker;;;;1-1-1');

/* --------------------------------------------------
	New tables
-------------------------------------------------- */

ExtensionManagementUtility::allowTableOnStandardPages('tx_odsosm_marker');

ExtensionManagementUtility::allowTableOnStandardPages('tx_odsosm_track');
ExtensionManagementUtility::addToInsertRecords('tx_odsosm_track');

ExtensionManagementUtility::allowTableOnStandardPages('tx_odsosm_vector');
ExtensionManagementUtility::addToInsertRecords('tx_odsosm_vector');

/* --------------------------------------------------
	Plugin
-------------------------------------------------- */
ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY . '/pi1/flexform_basic.xml');

if (ExtensionManagementUtility::isLoaded('cal')) {
	ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY . '/pi1/flexform_cal.xml');
}

if (ExtensionManagementUtility::isLoaded('tt_address')) {
	ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY . '/pi1/flexform_ttaddress.xml');
}

if (ExtensionManagementUtility::isLoaded('cal') && ExtensionManagementUtility::isLoaded('tt_address')) {
	ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY . '/pi1/flexform_cal_ttaddress.xml');
}

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] ='pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';

if (TYPO3_MODE == 'BE') {

	ExtensionManagementUtility::insertModuleFunction(
		'web_func',
		'tx_odsosm_geocodeWizard',
		ExtensionManagementUtility::extPath($_EXTKEY) . 'func_wizards/class.tx_odsosm_geocodeWizard.php',
		'LLL:EXT:ods_osm/locallang.xml:wiz_geocode'
	);
}

if (VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getCurrentTypo3Version()) < 8000000) {
	// TYPO3 6.2 compatibility
	// Register colorpicker wizard
	ExtensionManagementUtility::addModulePath(
		'wizard_coordinatepicker',
		ExtensionManagementUtility::extPath($_EXTKEY) . 'wizard/'
	);
}

/**
 * Register icons
 */
$iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ods_osm',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:ods_osm/Resources/Public/Icons/osm.png']
);

ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ods_osm/Configuration/TSConfig/ContentElementWizard.typoscript">');
?>
