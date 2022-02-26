<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST, OPTIONS');
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

$database = new Database();
$db = $database->getConnection();

$ha_kyc_personal_info = new Ha_Kyc_Personal_Info($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// make sure data is not empty
if (!isEmpty($data->customer_firstname)
    && !isEmpty($data->customer_lastname)
    && !isEmpty($data->customer_dob)
    && !isEmpty($data->customer_gender)
    && !isEmpty($data->customer_phone_no)
    && !isEmpty($data->customer_email)
    && !isEmpty($data->customer_residence_type)
    && !isEmpty($data->customer_house_number)
    && !isEmpty($data->customer_house_address)
    && !isEmpty($data->customer_state)
    && !isEmpty($data->customer_city)
    && !isEmpty($data->customer_lga)
    && !isEmpty($data->customer_country)
    && !isEmpty($data->customer_stay_duration)) {

    // set ha_kyc_personal_info property values

    if (!isEmpty($data->customer_firstname)) {
        $ha_kyc_personal_info->customer_firstname = $data->customer_firstname;
    } else {
        $ha_kyc_personal_info->customer_firstname = '';
    }
    if (!isEmpty($data->customer_lastname)) {
        $ha_kyc_personal_info->customer_lastname = $data->customer_lastname;
    } else {
        $ha_kyc_personal_info->customer_lastname = '';
    }
    if (!isEmpty($data->customer_dob)) {
        $ha_kyc_personal_info->customer_dob = date('Y-m-d', strtotime($data->customer_dob));
    } else {
        $ha_kyc_personal_info->customer_dob = date('Y-m-d');
    }
    if (!isEmpty($data->customer_gender)) {
        $ha_kyc_personal_info->customer_gender = $data->customer_gender;
    } else {
        $ha_kyc_personal_info->customer_gender = '';
    }
    if (!isEmpty($data->customer_phone_no)) {
        $ha_kyc_personal_info->customer_phone_no = $data->customer_phone_no;
    } else {
        $ha_kyc_personal_info->customer_phone_no = '';
    }
    if (!isEmpty($data->customer_email)) {
        $ha_kyc_personal_info->customer_email = $data->customer_email;
    } else {
        $ha_kyc_personal_info->customer_email = '';
    }
    if (!isEmpty($data->customer_residence_type)) {
        $ha_kyc_personal_info->customer_residence_type = $data->customer_residence_type;
    } else {
        $ha_kyc_personal_info->customer_residence_type = 'tenant';
    }
    if (!isEmpty($data->customer_house_number)) {
        $ha_kyc_personal_info->customer_house_number = $data->customer_house_number;
    } else {
        $ha_kyc_personal_info->customer_house_number = '1';
    }
    if (!isEmpty($data->customer_house_address)) {
        $ha_kyc_personal_info->customer_house_address = $data->customer_house_address;
    } else {
        $ha_kyc_personal_info->customer_house_address = 'unknown';
    }
    if (!isEmpty($data->customer_state)) {
        $ha_kyc_personal_info->customer_state = $data->customer_state;
    } else {
        $ha_kyc_personal_info->customer_state = 'Plateau';
    }
    if (!isEmpty($data->customer_city)) {
        $ha_kyc_personal_info->customer_city = $data->customer_city;
    } else {
        $ha_kyc_personal_info->customer_city = 'Jos';
    }
    if (!isEmpty($data->customer_lga)) {
        $ha_kyc_personal_info->customer_lga = $data->customer_lga;
    } else {
        $ha_kyc_personal_info->customer_lga = 'Jos South';
    }
    if (!isEmpty($data->customer_country)) {
        $ha_kyc_personal_info->customer_country = $data->customer_country;
    } else {
        $ha_kyc_personal_info->customer_country = 'Nigeria';
    }
    if (!isEmpty($data->customer_stay_duration)) {
        $ha_kyc_personal_info->customer_stay_duration = $data->customer_stay_duration;
    } else {
        $ha_kyc_personal_info->customer_stay_duration = '1';
    }


    $ha_kyc_personal_info->user_id = $profileData->id;
    $ha_kyc_personal_info->follow_up = 0;
    $ha_kyc_personal_info->comment = '';
    $ha_kyc_personal_info->created_at = date('Y-m-d H:m:s');
    $ha_kyc_personal_info->updated_at = date('Y-m-d H:m:s');

    $lastInsertedId = $ha_kyc_personal_info->create();
    // create the ha_kyc_personal_info
    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
    } // if unable to create the ha_kyc_personal_info, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_kyc_personal_info", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_kyc_personal_info. Data is incomplete.", "data" => ""));
}
?>
