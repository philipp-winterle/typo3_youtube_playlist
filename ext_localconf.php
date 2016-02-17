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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, "constants", '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:youtube_playlist/Configuration/TypoScript/constants.txt">');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, "setup", '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:youtube_playlist/Configuration/TypoScript/setup.txt">');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
	'mod {
     wizards.newContentElement.wizardItems.plugins {
        elements {
			tx_youtube_playlist {
			   icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif
			   title = YouTube Playlist
			   description = Select all playlists of a YouTube channel to display them in a specific order in the frontend.
			   params = &defVals[tt_content][CType]=list&defVals[tt_content][list_type]=youtubeplaylist_youtubeplaylistplugin
			}
        }
     }
   }'
);