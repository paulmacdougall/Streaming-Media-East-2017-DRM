To use this example, you must first get credentials for Bitmovin's API (https://bitmovin.com/bitmovins-video-api/) and credentials or configuration from EZDRM's Multi-DRM system. This demo also requires the Bitmovin PHP Client, available at: https://github.com/bitmovin/bitmovin-php

Please reach out to sales@bitmovin.com for further information.

###In "encode.php" you will need to update these configuration items with your details to encode a test video:
```
$videoInputPath = '<SOURCE VIDEO URL>';
$awsBucketName = '<AWS BUCKET NAME>';
$awsPrefix = "<AWS PATH>";
$awsAccessKey = '<AWS ACCESS KEY>';
$awsSecretKey = 'AWS SECRET KEY>';
$cenc_drm_key = '<DRM KEY IN HEX';
$cenc_drm_kid= '<DRM KID IN HEX>';
$cenc_widevine_pssh = '<PSSH>';
$cenc_playready_laurl = '<PLAYREADY LA URL>';
$client = new BitmovinClient('<BITMOVIN API KEY>');
```

###In "sme.html" you will need to update these configuration items with your details to create a webpage with a test player:
```
key:       "<YOUR PLAYER KEY GOES HERE, CONTACT SALES@BITMOVIN.COM FOR MORE INFO>",
dash:        "//your.dash.manifest.url.mpd, must be served over SSL for Chrome",
poster:      "//your.poster.image.url"
drm : {
widevine : {
  LA_URL: 'WIDEVINE LA URL'
},
playready : {
  LA_URL : 'PLAYREADY LA URL'
}
```
