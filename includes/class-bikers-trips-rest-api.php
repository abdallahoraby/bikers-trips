<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400'); // cache for 1 day
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");


$separator          = '/';
$current_DIR             = __DIR__;
$array              = explode( $separator, $current_DIR );
$plugin_folder_name = end( $array );

define("PLUGIN_PATH", __DIR__); // absolute path for folder of our plugin
define("PLUGIN_DIR_NAME", $plugin_folder_name); // absolute path for folder of our plugin
define("API_PATH", "bikers/v1");
define("SITE_URL", get_site_url());
define("CONSUMER_KEY", 'ck_4d7e50825654249863a57a3ed56e6d759fbf6af8' );
define("CONSUMER_SECRET", 'cs_fcb19410c5560442be9bdfb116e4a6685ad3eb41' );
define("DEFAULT_IMAGE", SITE_URL . '/wp-content/uploads/woocommerce-placeholder-300x300.png' );




$functions = [
    'getImgsUrl',
    'emptyFolder',
    'getProductReviews',
    'convertToTimeAgo',
    'getTranslation',
];


foreach ($functions as $function) {
    include(PLUGIN_PATH . "/../functions/$function.php");
}

$endPoints = [
    'getAccess',
    'userLogin',
    'getUserData',
    'updateUserProfile',
    'addTrip',
    'listUserTrips',
    'listUpcomingTrips',
    'listLiveTrips',
    'listFinishedTrips',
    'addTripMembers',
    'listTripMembers',
    'startTrip',
    'trackTrip',
    'finishTrip',
    'getLiveTripPosition',


];
foreach ($endPoints as $endpoint) {
    require(PLUGIN_PATH . "/../endpoints/$endpoint.php");
}