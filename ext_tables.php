<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


// Require GOOGLE API
$composerAutoloadFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY)
	. 'Libraries/autoload.php';
require_once($composerAutoloadFile);

// REQUIRE CONFIG
$configFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY)
	. 'Classes/Utility/Configuration.php';
require_once($configFile);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Youtubeplaylistplugin',
	'YouTube Playlist Plugin'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'YouTube Playlist Configuration');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_youtubeplaylist_domain_model_playlist', 'EXT:youtube_playlist/Resources/Private/Language/locallang_csh_tx_youtubeplaylist_domain_model_playlist.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_youtubeplaylist_domain_model_playlist');
$GLOBALS['TCA']['tx_youtubeplaylist_domain_model_playlist'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:youtube_playlist/Resources/Private/Language/locallang_db.xlf:tx_youtubeplaylist_domain_model_playlist',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'enablecolumns' => array(

		),
		'searchFields' => '',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/PlayList.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_youtubeplaylist_domain_model_playlist.gif'
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$pluginName = strtolower('Youtubeplaylistplugin');
$pluginSignature = $extensionName . '_' . $pluginName;

/**
 * Register Plugin as Page Content
 */
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform.xml');

// Add  plugin to new element wizard
//$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] =
//	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Wizards/' . $_EXTKEY . '_wizicon.php';