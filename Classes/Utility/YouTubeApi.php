<?php
namespace Powrup\YoutubePlaylist\Utility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Philipp Winterle <winterle.p@st-sportservice.com>, ST-Sportservice
 *
 *  All rights reserved
 *
 *
 ***************************************************************/

class YouTubeApi {
    /**
     * @var \Google_Client
     */
    protected $apiClient;
    /**
     * @var \Google_Service_YouTube
     */
    private $apiService;
    /**
     * @var String
     */
    private $channelId;

    /**
     * @return String
     */
    public function getChannelId() {
        return $this->channelId;
    }

    /**
     * @param String $channelId
     */
    public function setChannelId($channelId) {
        $this->channelId = $channelId;
    }

    public function isChannelIdSet() {
        return isset($this->channelId);
    }

    /**
     * @return mixed
     */
    public function getApiClient() {
        return $this->apiClient;
    }

    /**
     * @param mixed $apiClient
     */
    public function setApiClient($apiClient) {
        $this->apiClient = $apiClient;
    }

    /**
     * @return mixed
     */
    public function getApiService() {
        return $this->apiService;
    }

    /**
     * @param mixed $apiService
     */
    public function setApiService($apiService) {
        $this->apiService = $apiService;
    }

    function __construct($API_SERVER_TOKEN) {
        $this->apiClient = new \Google_Client();
        $this->apiClient->setApplicationName("EHF-YouTube-Playlist");
        $this->apiClient->setDeveloperKey($API_SERVER_TOKEN);

        $this->apiService = new \Google_Service_YouTube($this->apiClient);
    }

    /**
     * retrieves the top 50 of the channels playlists id's
     *
     * @param $part
     * @param int $maxResults
     * @param null $language
     * @return bool|\Google_Service_YouTube_PlaylistListResponse
     */
    public function listPlaylists($part, $maxResults = 50, $language = null) {
        try {
            if ($this->isChannelIdSet()) {
                $playLists = $this->apiService->playlists->listPlaylists($part, array("channelId" => $this->getChannelId(), "maxResults" => $maxResults,));
                return $playLists;
            }
        } catch (\Google_Service_Exception $e) {
            error_log(sprintf('YouTube Playlist Extension: A service error occurred: %s', htmlspecialchars($e->getMessage())));
        } catch (\Google_Exception $e) {
            error_log(sprintf('YouTube Playlist Extension: An client error occurred: %s', htmlspecialchars($e->getMessage())));
        }
        return false;
    }

    public function getPlaylist($playlistId, $language = null) {
        try {
            if (isset($playlistId)) {
                $playLists = $this->apiService->playlists->listPlaylists("snippet", array("id" => $playlistId));
                return $playLists;
            }
        } catch (\Google_Service_Exception $e) {
            error_log(sprintf('YouTube Playlist Extension: A service error occurred: %s', htmlspecialchars($e->getMessage())));
        } catch (\Google_Exception $e) {
            error_log(sprintf('YouTube Playlist Extension: An client error occurred: %s', htmlspecialchars($e->getMessage())));
        }
        return false;
    }

    /**
     * @param $playlistId
     * @return bool|\Google_Service_YouTube_PlaylistItemListResponse
     */
    public function getPlaylistVideos($playlistId) {
        try {
            if (isset($playlistId))
            $playlistItemsResponse = $this->apiService->playlistItems->listPlaylistItems('snippet,status', array(
                'playlistId' => $playlistId,
                'maxResults' => 50
            ));

            return $playlistItemsResponse;

        } catch (\Google_Service_Exception $e) {
            error_log(sprintf('YouTube Playlist Extension: A service error occurred: %s', htmlspecialchars($e->getMessage())));
        } catch
        (\Google_Exception $e) {
            error_log(sprintf('YouTube Playlist Extension: An client error occurred: %s', htmlspecialchars($e->getMessage())));
        }
        return false;
    }
}