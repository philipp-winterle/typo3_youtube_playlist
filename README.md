# typo3_youtube_playlist
TYPO3 Extension to select and display youtube playlists

Just install and use as a frontend plugin. 

The composer.json needs to be modified in order to use composer to update the google api client.
The "require-bak" property needs to be like this in order to work with TYPO3 6.2.* because of their own composer implementation. 
Otherwise it will throw an error because of missing installed extension google api client. 

# Requirements
At the moment this extension needs jQuery 1.9+ to work in the frontend. In the next version jQuery will be inserted in the frontend output aswell.

# Usage

In the backend configuration of the plugin you need to insert your channel id and the server api token for the YouTube Data API. 
After you did that press save and the second tab will be filled with playlists of this channel. 
You can now select an amount of playlists you want to be displayed in the frontend. 
The first selected playlists will be the main playlist and is displayed next to the video player. All further playlists will be displayed at the bottom of the player. 


