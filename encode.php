<?php

use Bitmovin\api\enum\CloudRegion;
use Bitmovin\api\model\encodings\drms\cencSystems\CencMarlin;
use Bitmovin\BitmovinClient;
use Bitmovin\configs\audio\AudioStreamConfig;
use Bitmovin\configs\drm\cenc\CencPlayReady;
use Bitmovin\configs\drm\cenc\CencWidevine;
use Bitmovin\configs\drm\CencDrm;
use Bitmovin\configs\EncodingProfileConfig;
use Bitmovin\configs\JobConfig;
use Bitmovin\configs\manifest\DashOutputFormat;
use Bitmovin\configs\video\H264VideoStreamConfig;
use Bitmovin\input\HttpInput;
use Bitmovin\output\S3Output;

require_once __DIR__ . '/../vendor/autoload.php';

// CONFIGURATION
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

// CREATE ENCODING PROFILE
$encodingProfile = new EncodingProfileConfig();
$encodingProfile->name = 'CreateContentProtectedDashTest';
$encodingProfile->cloudRegion = CloudRegion::GOOGLE_EUROPE_WEST_1;

// CREATE VIDEO STREAM CONFIGS
$videoStreamConfig_1080 = new H264VideoStreamConfig();
$videoStreamConfig_1080->input = new HttpInput($videoInputPath);
$videoStreamConfig_1080->width = 1920;
$videoStreamConfig_1080->height = 1080;
$videoStreamConfig_1080->bitrate = 4800000;
$videoStreamConfig_1080->rate = 30.0;
$encodingProfile->videoStreamConfigs[] = $videoStreamConfig_1080;

$videoStreamConfig_720 = new H264VideoStreamConfig();
$videoStreamConfig_720->input = new HttpInput($videoInputPath);
$videoStreamConfig_720->width = 1280;
$videoStreamConfig_720->height = 720;
$videoStreamConfig_720->bitrate = 2400000;
$videoStreamConfig_720->rate = 30.0;
$encodingProfile->videoStreamConfigs[] = $videoStreamConfig_720;

$videoStreamConfig_480 = new H264VideoStreamConfig();
$videoStreamConfig_480->input = new HttpInput($videoInputPath);
$videoStreamConfig_480->width = 854;
$videoStreamConfig_480->height = 480;
$videoStreamConfig_480->bitrate = 1200000;
$videoStreamConfig_480->rate = 30.0;
$encodingProfile->videoStreamConfigs[] = $videoStreamConfig_480;

$videoStreamConfig_360 = new H264VideoStreamConfig();
$videoStreamConfig_360->input = new HttpInput($videoInputPath);
$videoStreamConfig_360->width = 640;
$videoStreamConfig_360->height = 360;
$videoStreamConfig_360->bitrate = 800000;
$videoStreamConfig_360->rate = 30.0;
$encodingProfile->videoStreamConfigs[] = $videoStreamConfig_360;

$videoStreamConfig_240 = new H264VideoStreamConfig();
$videoStreamConfig_240->input = new HttpInput($videoInputPath);
$videoStreamConfig_240->width = 426;
$videoStreamConfig_240->height = 240;
$videoStreamConfig_240->bitrate = 400000;
$videoStreamConfig_240->rate = 30.0;
$encodingProfile->videoStreamConfigs[] = $videoStreamConfig_240;

// CREATE AUDIO STREAM CONFIG
$audioConfig = new AudioStreamConfig();
$audioConfig->input = new HttpInput($videoInputPath);
$audioConfig->bitrate = 128000;
$audioConfig->rate = 48000;
$audioConfig->name = 'English';
$audioConfig->lang = 'en';
$audioConfig->position = 1;
$encodingProfile->audioStreamConfigs[] = $audioConfig;

// CREATE JOB CONFIG
$jobConfig = new JobConfig();
// ASSIGN OUTPUT
$jobConfig->output = new S3Output($awsAccessKey, $awsSecretKey, $awsBucketName, $awsPrefix);
// ASSIGN ENCODING PROFILES TO JOB
$jobConfig->encodingProfile = $encodingProfile;
// ENABLE DASH OUTPUT WITH CENC
$outputFormat = new DashOutputFormat();
$outputFormat->cenc = new CencDrm($cenc_drm_key, $cenc_drm_kid);
$outputFormat->cenc->setWidevine(new CencWidevine($cenc_widevine_pssh));
$outputFormat->cenc->setPlayReady(new CencPlayReady($cenc_playready_laurl));
$outputFormat->cenc->setMarlin(new CencMarlin());
$jobConfig->outputFormat[] = $outputFormat;

// RUN JOB AND WAIT UNTIL IT HAS FINISHED
$client->runJobAndWaitForCompletion($jobConfig);
