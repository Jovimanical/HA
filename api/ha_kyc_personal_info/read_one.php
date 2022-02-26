<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
            header('Access-Control-Allow-Credentials: true');
            header("HTTP/1.1 200 OK");
            return;
        }
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_personal_info.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_kyc_personal_info object
$ha_kyc_personal_info = new Ha_Kyc_Personal_Info($db);

// set ID property of record to read
$ha_kyc_personal_info->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_kyc_personal_info to be edited
$ha_kyc_personal_info->readOne();

if ($ha_kyc_personal_info->id != null) {
    // create array
    $ha_kyc_personal_info_arr = array(

        "id" => $ha_kyc_personal_info->id,
        "customer_firstname" => html_entity_decode($ha_kyc_personal_info->customer_firstname),
        "customer_lastname" => html_entity_decode($ha_kyc_personal_info->customer_lastname),
        "customer_dob" => html_entity_decode($ha_kyc_personal_info->customer_dob),
        "customer_gender" => $ha_kyc_personal_info->customer_gender,
        "customer_phone_no" => $ha_kyc_personal_info->customer_phone_no,
        "customer_email" => $ha_kyc_personal_info->customer_email,
        "customer_residence_type" => $ha_kyc_personal_info->customer_residence_type,
        "customer_house_number" => $ha_kyc_personal_info->customer_house_number,
        "customer_house_address" => html_entity_decode($ha_kyc_personal_info->customer_house_address),
        "customer_state" => $ha_kyc_personal_info->customer_state,
        "customer_city" => $ha_kyc_personal_info->customer_city,
        "customer_lga" => $ha_kyc_personal_info->customer_lga,
        "customer_country" => $ha_kyc_personal_info->customer_country,
        "customer_stay_duration" => $ha_kyc_personal_info->customer_stay_duration,
        "user_id" => $ha_kyc_personal_info->user_id,
        "follow_up" => $ha_kyc_personal_info->follow_up,
        "comment" => $ha_kyc_personal_info->comment,
        "created_at" => $ha_kyc_personal_info->created_at,
        "updated_at" => $ha_kyc_personal_info->updated_at
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_kyc_personal_info found", "data" => $ha_kyc_personal_info_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_kyc_personal_info does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_kyc_personal_info does not exist.", "data" => ""));
}
?>
