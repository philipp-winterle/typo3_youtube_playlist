<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "youtube_playlist"
 *
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
    'title' => 'YouTube Playlist',
    'description' => 'This extension provides an interface to display a specific playlist on your page',
    'category' => 'plugin',
    'author' => 'Philipp Winterle',
    'author_email' => 'winterle.p@gmail.com',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'version' => '1.0.8',
    'constraints' => array(
        'depends' => array(
            'extbase' => '6.2.0-7.6.99',
			'fluid' => '6.2.0-7.6.99',
			'typo3' => '6.2.1-7.6.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);

