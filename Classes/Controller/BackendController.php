<?php
namespace Powrup\YoutubePlaylist\Controller;

use Powrup\YoutubePlaylist\Utility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Philipp Winterle <winterle.p@st-sportservice.com>, ST-Sportservice
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

/**
 * PlayListController
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	const EXTENSION_NAME = 'youtube_playlist';
	const PLUGIN_NAME = 'youtubeplaylistplugin';
	const CONTROLLER_NAME = 'Backend'; // Controller must be set to suppress warnings

	/**
	 * @var \Powrup\YoutubePlaylist\Utility\YouTubeApi
	 * @inject
	 */
	private $youTubeApi;
	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var  \TYPO3\CMS\Extbase\Core\Bootstrap
	 */
	protected $bootstrap;

	public function __construct() {
		parent::__construct();
	}

	public function init(&$PA, &$fobj) {
		$configuration['extensionName'] = self::EXTENSION_NAME;
		$configuration['pluginName'] = self::PLUGIN_NAME;

		$this->initBackendRequirements();
	}

	/**
	 * Load JQuery Files
	 *
	 */
	public function initBackendRequirements() {
		/**
		 * @var \TYPO3\CMS\Backend\Template\DocumentTemplate $doc
		 */
		$doc = $this->getDocInstance();
		$extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath(self::EXTENSION_NAME);

		$pageRenderer = $doc->getPageRenderer();
		$pageRenderer->loadRequireJs();
		$pageRenderer->addRequireJsConfiguration(
			array(
				'paths' => array(
					'jquery' => $extRelPath . 'Resources/Public/JavaScript/jQuery/jquery-1.11.3.min',
					'jquery-ui' => $extRelPath . 'Resources/Public/JavaScript/jQuery/jquery-ui-1.11.4.min',
					'tx_youtube_playlist_base' => $extRelPath . 'Resources/Public/JavaScript/base',
					"tx_youtube_playlist_uikit" => $extRelPath . 'Resources/Public/JavaScript/uikit/uikit.min'
				),
			)
		);
		$pageRenderer->loadRequireJsModule("jquery");
		$pageRenderer->loadRequireJsModule("jquery-ui");
		$pageRenderer->loadRequireJsModule("tx_youtube_playlist_base");
		//$pageRenderer->loadRequireJsModule("tx_youtube_playlist_uikit");

		$compress = true;

		// Backend
		$pageRenderer->addCssFile($extRelPath . 'Resources/Public/StyleSheets/uikit/css/uikit.docs.min.css', 'stylesheet', 'all', '', $compress);
		$pageRenderer->addCssFile($extRelPath . 'Resources/Public/StyleSheets/backend.css', 'stylesheet', 'all', '', $compress);
	}

	/*
     * Important Service Functions
     */
	/**
	 * Check if the current backend user has access to this module
	 */
	protected function checkBackendAccessRights() {
		$backendUser = $GLOBALS['BE_USER'];
		/** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $backendUser */
		$backendUser->modAccess(array('name' => self::PLUGIN_NAME, 'access' => 'user, group'), TRUE);
	}

	/**
	 * Gets instance of template if exists or create a new one.
	 * Saves instance in viewHelperVariable\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance template $doc
	 *
	 * @return \TYPO3\CMS\Backend\Template\DocumentTemplate
	 */
	protected function getDocInstance() {
		$doc = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
		$doc->backPath = $GLOBALS['BACK_PATH'];
		return $doc;
	}

	/*
	 * Backend ACTIONs
	 */

	/**
	 * action getYouTubePlayLists
	 *
	 * @param array $PA
	 * @param  \TYPO3\CMS\Backend\Form\FormEngine $fobj
	 * @return \Google_Service_YouTube_PlaylistItemListResponse
	 */
	public function getYouTubePlayLists(&$PA, &$fobj) {
		$this->init($PA, $fobj);

		/** @var \Powrup\YoutubePlaylist\Utility\PlayListSelector $playListSelector */
		$playListSelector = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\Powrup\\YoutubePlaylist\\Utility\\PlayListSelector');
		$content = $playListSelector->getPlayListList($PA, $fobj, 20);

		return $content;
	}

}