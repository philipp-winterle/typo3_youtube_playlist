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

class Configuration {

    /**
     * gets a TS Array by path
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     * @param $typoscriptObjectPath
     * @return array
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public static function getTsArrayByPath($configurationManager, $typoscriptObjectPath) {
        $setup = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $pathSegments = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('.', $typoscriptObjectPath);

        foreach ($pathSegments as $segment) {
            if (!array_key_exists(($segment . '.'), $setup)) {
                throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('TypoScript object path "' . htmlspecialchars($typoscriptObjectPath) . '" does not exist', 1253191023);
            }
            $setup = $setup[$segment . '.'];
        }
        return $setup;
    }
}