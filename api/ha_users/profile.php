<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // The request is using the POST method
    header("HTTP/1.1 200 OK");
    return;
}

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_users object
$ha_users = new Ha_Users($db);

// set ID property of record to read
$ha_users->id = $profileData->id;

// read the details of ha_users to be edited
$ha_users->readProfileOne();

if ($ha_users->id != null) {
    // create array
    $ha_users_arr = array(
        "id" => $ha_users->id,
        "firstname" => $ha_users->firstname,
        "lastname" => $ha_users->lastname,
        "username" => $ha_users->username,
        "email" => $ha_users->email,
        "country_code" => $ha_users->country_code,
        "mobile" => $ha_users->mobile,
        "profileImage" => html_entity_decode($ha_users->profileImage),
        "address" => $ha_users->address,
        "status" => $ha_users->status,
        "roles" => html_entity_decode($ha_users->roles),
        "created_at" => $ha_users->created_at,
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_users found", "data" => $ha_users_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_users does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_users does not exist.", "data" => ""));
}
?>
