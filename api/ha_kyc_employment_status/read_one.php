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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kyc_employment_status.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_kyc_employment_status object
$ha_kyc_employment_status = new Ha_Kyc_Employment_Status($db);

// set ID property of record to read
$ha_kyc_employment_status->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of ha_kyc_employment_status to be edited
$ha_kyc_employment_status->readOne();

if ($ha_kyc_employment_status->id != null) {
    // create array
    $ha_kyc_employment_status_arr = array(

        "id" => $ha_kyc_employment_status->id,
        "customer_employment_status" => html_entity_decode($ha_kyc_employment_status->customer_employment_status),
        "customer_employer_name" => html_entity_decode($ha_kyc_employment_status->customer_employer_name),
        "customer_employer_office_number" => html_entity_decode($ha_kyc_employment_status->customer_employer_office_number),
        "customer_employer_address" => html_entity_decode($ha_kyc_employment_status->customer_employer_address),
        "customer_employer_nearest_bustop" => html_entity_decode($ha_kyc_employment_status->customer_employer_nearest_bustop),
        "customer_employer_state" => html_entity_decode($ha_kyc_employment_status->customer_employer_state),
        "customer_employer_city" => html_entity_decode($ha_kyc_employment_status->customer_employer_city),
        "customer_employer_lga" => html_entity_decode($ha_kyc_employment_status->customer_employer_lga),
        "customer_employer_country" => html_entity_decode($ha_kyc_employment_status->customer_employer_country),
        "customer_employer_doe" => html_entity_decode($ha_kyc_employment_status->customer_employer_doe),
        "customer_account_bvn" => html_entity_decode($ha_kyc_employment_status->customer_account_bvn),
        "customer_account_monthly_salary" => html_entity_decode($ha_kyc_employment_status->customer_account_monthly_salary),
        "customer_account_primary_bank" => html_entity_decode($ha_kyc_employment_status->customer_account_primary_bank),
        "customer_account_primary_bank_account" => html_entity_decode($ha_kyc_employment_status->customer_account_primary_bank_account),
        "user_id" => $ha_kyc_employment_status->user_id,
        "follow_up" => $ha_kyc_employment_status->follow_up,
        "comment" => $ha_kyc_employment_status->comment,
        "created_at" => $ha_kyc_employment_status->created_at,
        "updated_at" => $ha_kyc_employment_status->updated_at
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_kyc_employment_status found", "data" => $ha_kyc_employment_status_arr));
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user ha_kyc_employment_status does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_kyc_employment_status does not exist.", "data" => ""));
}
?>
