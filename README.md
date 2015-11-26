# typo3_youtube_playlist
TYPO3 Extension to select and display youtube playlists

Just install and use as a frontend plugin. 

The composer.json needs to be modified in order to use composer to update the google api client.
The "require-bak" property needs to be like this in order to work with TYPO3 6.2.* because of their own composer implementation. 
Otherwise it will throw an error because of missing installed extension google api client. 

# Requirements
    TYPO3 6.2 - 7.6

# Install
    - Download and install the extension from TER or Composer
    - Add the TypoScript templates into your root or extension template

# Usage
    - Add the frontend plugin via "General Plugin"
    - Fill in the ChannelID of the playlists you want to display
    - Insert your YouTube Data API Server Token (https://console.developers.google.com/apis/api) | It needs to be a SERVER TOKEN!!
    - Save the Plugin Content Element and switch to the "Source"-Tab
    - If you've done right you will see all the available playlists of the channel
    - Select as many playlists as you want to be displayed at the frontend
        - The first playlist selected will be the main playlist. All further playlists are displayed under the player window

If you like this extension and if you want to ensure it is enhanced you can donate to me (https://www.paypal.me/PhilippWinterle)        

