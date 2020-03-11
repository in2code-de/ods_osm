<?php
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:ods_osm/locallang_db.xml:tt_content.list_type_pi1',
        'ods_osm_pi1'
    ],
    'list_type'
);
