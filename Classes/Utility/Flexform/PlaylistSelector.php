<?php
namespace Powrup\YoutubePlaylist\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;



/**
 * Class provides dataProvider for FlexForm
 *
 *
 * @author Philipp Winterle
 * @package Utility
 */
class PlayListSelector {

    const EXTENSION_NAME = 'youtube_playlist';
    const PLUGIN_NAME = 'youtubeplaylistplugin';
    const CONTROLLER_NAME = 'Backend'; // Controller must be set to suppress warnings


    /**
     * Extbase Object Manager
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Powrup\YoutubePlaylist\Utility\YouTubeApi
     * @inject
     */
    private $youTubeApi;

    private $apiServerToken;
    private $ytChannelId;

    /**
     * Init the extbase Context and the configurationBuilder
     *
     * @throws \Exception
     */
    protected function init(&$PA) {
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        if (is_array($PA['row']['pi_flexform'])) {
            // TYPO3 7.5 and newer delivers an array
            $this->apiServerToken = $PA['row']['pi_flexform']['data']['sDefault']['lDEF']['settings.apiServerToken']['vDEF'];
            $this->ytChannelId = $PA['row']['pi_flexform']['data']['sDefault']['lDEF']['settings.channelId']['vDEF'];
        } else {
            // TYPO3 7.4 or older delivers a string
            $flexForm = GeneralUtility::xml2array($PA['row']['pi_flexform']);
            if ( is_array($flexForm) && isset($flexForm['data']['sDefault']['lDEF']['settings.apiServerToken']['vDEF'])) {
                $this->apiServerToken = $flexForm['data']['sDefault']['lDEF']['settings.apiServerToken']['vDEF'];
            }
            if (is_array($flexForm) && isset($flexForm['data']['sDefault']['lDEF']['settings.channelId']['vDEF'])) {
                $this->ytChannelId = $flexForm['data']['sDefault']['lDEF']['settings.channelId']['vDEF'];
            }
        }

        if (!isset($this->apiServerToken)) {
            error_log("YouTube Playlist - PlaylistSelector: API Server Token could not be accessed.");
        }
        if (!isset($this->ytChannelId)) {
            error_log("YouTube Playlist - PlaylistSelector: YouTube Channel ID could not be accessed.");
        }

        $this->youTubeApi = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Powrup\\YoutubePlaylist\\Utility\\YouTubeApi', $this->apiServerToken);

        // Set Channel ID
        $this->youTubeApi->setChannelId($this->ytChannelId);
    }



    /**
     * Get Album List as JSON
     */
    public function getPlayListList(&$PA, &$fobj, $maxCount = 50) {
        $this->init($PA);

        /** @var \Google_Service_YouTube_Playlists_Resource $playLists */
        $playLists = $this->youTubeApi->listPlaylists("snippet,contentDetails", $maxCount);
        $playListsItems = $this->getYouTubePlayListItemList($playLists);

        $template = GeneralUtility::getFileAbsFileName('EXT:youtube_playlist/Resources/Private/Templates/Backend/FlexForm/PlayListList.html');
        $renderer = $this->getFluidRenderer();

        $renderer->setTemplatePathAndFilename($template);

        $renderer->assign('playlists', $playListsItems);
        $renderer->assign('PA', $PA);

        $content = $renderer->render();

        //$this->extbaseShutdown();

        return $content;
    }

    /**
     * Build A Fluid Renderer
     * @return \TYPO3\CMS\Fluid\View\TemplateView
     */
    protected function getFluidRenderer() {

        /* @var $request \TYPO3\CMS\Extbase\Mvc\Request */
        $request = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Request');
        $request->setControllerExtensionName(self::EXTENSION_NAME);
        $request->setPluginName(self::PLUGIN_NAME);
        $request->setControllerName(self::CONTROLLER_NAME);

        $fluidRenderer = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\TemplateView');
        $controllerContext = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext');
        $controllerContext->setRequest($request);
        $fluidRenderer->setControllerContext($controllerContext);

        return $fluidRenderer;
    }

    // YOUTUBE FUNCTIONS
    /**
     * Try to return the playlist items
     * If no items are found return false
     *
     * @param \Google_Service_YouTube_Playlists_Resource $playlists_Resource
     * @return bool
     */
    private function getYouTubePlayListItemList($playlists_Resource) {
        $playListsItems = false;
        try {
            $playListsItems = $playlists_Resource["modelData"]["items"];
        } catch(\Exception $e) {
            error_log($e.getMessage());
        }

        return $playListsItems;
    }
}
