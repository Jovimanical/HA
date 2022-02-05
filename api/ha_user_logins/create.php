<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';

// instantiate ha_user_logins object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_user_logins.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$ha_user_logins = new Ha_User_Logins($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (!isEmpty($data->user_id)) {

    // set ha_user_logins property values

    if (!isEmpty($data->user_id)) {

    } else {
        $ha_user_logins->user_id = '0';
    }

    $USER_IP = getIpAddress();
    $ua = getBrowser();

//call api
    $RETURNED_URL = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $USER_IP);

//decode json data
    $getInfo = json_decode($RETURNED_URL);


    $ha_user_logins->user_id = $data->user_id;
    $ha_user_logins->user_ip = getIpAddress();
    $ha_user_logins->city = $getInfo->geoplugin_city || 'UNKNOWN';
    $ha_user_logins->country = $getInfo->geoplugin_countryName || 'UNKNOWN';
    $ha_user_logins->country_code = $getInfo->geoplugin_countryCode || 'UNKNOWN';
    $ha_user_logins->longitude = $getInfo->geoplugin_latitude || 'UNKNOWN';
    $ha_user_logins->latitude = $getInfo->geoplugin_longitude || 'UNKNOWN';
    $ha_user_logins->browser = "browser info: " . $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . " reports: <br >" . $ua['userAgent'];
    $ha_user_logins->os = $ua['platform'] || 'Unknown';
    $ha_user_logins->created_at = date('Y-m-d H:m:s');
    $ha_user_logins->updated_at = date('Y-m-d H:m:s');

    $lastInsertedId = $ha_user_logins->create();

    // create the ha_user_logins
    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
    } // if unable to create the ha_user_logins, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_user_logins", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_user_logins. Data is incomplete.", "data" => ""));
}
?>
