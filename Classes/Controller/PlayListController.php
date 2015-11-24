<?php
namespace Powrup\YoutubePlaylist\Controller;

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
class PlayListController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	/**
	 * @var \Powrup\YoutubePlaylist\Utility\YouTubeApi
	 */
	private $youTubeApi;

	const EXTENSION_NAME = 'youtube_playlist';
	const PLUGIN_NAME = 'youtubeplaylistplugin';
	const CONTROLLER_NAME = 'Youtubeplaylistplugin'; // Controller must be set to suppress warnings

	private $youtubeChannelId;
	private $youtubeServerApiToken;

	public function initializeAction() {
		$this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/StyleSheets/base.css" />');
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="' . ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/JavaScript/yt_pl.js" />');

		$this->youtubeChannelId = $this->settings["channelId"];
		$this->youtubeServerApiToken = $this->settings["apiServerToken"];

		$this->youTubeApi = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\Powrup\\YoutubePlaylist\\Utility\\YouTubeApi', $this->youtubeServerApiToken);
	 }

 /**
	 * action show
	 *
	 * @return void
	 */
	public function showAction() {
		$this->youTubeApi->setChannelId($this->youtubeChannelId);

		// Playlist IDs
		$playlistIdStr = $this->settings["selectedPlaylists"];
		$playlistIdArr = explode(",", $playlistIdStr);

		$videoArr = array();

		if (is_array($playlistIdArr)) { // if explode gives an array means: $playlistIdStr contains selected playlists

			// iterate through playlists and get videos
			foreach($playlistIdArr as $playlistId) {
				$tmpPlVideos = array();
				$tmpPlTitle = "";
				/* @var \Google_Service_YouTube_PlaylistListResponse $playList */
				$playList = $this->youTubeApi->getPlaylist($playlistId);
				$playListItems = $playList->getItems();
				if (isset($playListItems) && !empty($playListItems)) {
					/* @var \Google_Service_YouTube_Playlist $playListItem */
					$playListItem = $playListItems[0];
					/* @var \Google_Service_YouTube_PlaylistSnippet $playListData */
					$playListData = $playListItem->getSnippet();
					$tmpPlTitle = $playListData->getTitle();
				}

				/* @var \Google_Service_YouTube_PlaylistItemListResponse) $plVideos */
				$plVideos = $this->youTubeApi->getPlaylistVideos($playlistId);
				$plVideos = $plVideos->getItems();
				if (isset($plVideos) && !empty($plVideos)) {
					/* @var \Google_Service_YouTube_Video $plVideo */
					foreach($plVideos as $plVideo) {
						/* @var \Google_Service_YouTube_VideoStatus $videoStatus */
						$videoStatus = $plVideo->getStatus();
						$privacyStatus = $videoStatus->getPrivacyStatus();

						if ($privacyStatus === "public") {
							/* @var \Google_Service_YouTube_PlaylistItemSnippet $videoData */
							$videoData = $plVideo->getSnippet();
							/* @var \Google_Service_YouTube_ThumbnailDetails $thumbnails */
							$thumbnails = $videoData->getThumbnails();
							/* @var \Google_Service_YouTube_Thumbnail $thumbnail */
							$thumbnail = $thumbnails->getMedium();

							array_push($tmpPlVideos, array(
									"thumb" => $thumbnail->getUrl(),
									"title" => $videoData->getTitle(),
                                    "description" => $videoData->getDescription(),
									"id" => $videoData->getResourceId()["videoId"]
							));
						}
					}
					$videoArr[$playlistId] = [
							"Title" => $tmpPlTitle,
							"Videos" => $tmpPlVideos
					];
				}
			}
		}

		$this->view->assign('playlists', $videoArr);
	}

	private function formatVideoDuration($duration) {
		$start = new \DateTime('@0'); // Unix epoch
		$duration = new \DateInterval('PT24M30S');
		//$start->add($duration);
		//return $start->format("H:i:s");
		return $duration->format('%H:%i:%s'); // outputs: 00:24:30
	}

}