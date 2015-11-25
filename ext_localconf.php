<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$composerAutoloadFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY)
	. 'Libraries/autoload.php';
require_once($composerAutoloadFile);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Powrup.' . $_EXTKEY,
	'Youtubeplaylistplugin',
	array(
		'PlayList' => 'show',
	),
	// non-cacheable actions
	array(
		'PlayList' => '',
	)
);

// Flexform record selector
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Utility/Flexform/PlayListSelector.php';