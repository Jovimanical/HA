<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: PUT, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: PUT, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
            header('Access-Control-Allow-Credentials: true');
            header("HTTP/1.1 200 OK");
            return;
        }
    }
}

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

// get id of ha_kyc_employment_status to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_kyc_employment_status to be edited
$ha_kyc_employment_status->id = $data->id;

if (
    !isEmpty($data->customer_employment_status)
    && !isEmpty($data->customer_employer_name)
    && !isEmpty($data->customer_employer_office_number)
    && !isEmpty($data->customer_employer_address)
    && !isEmpty($data->customer_employer_nearest_bustop)
    && !isEmpty($data->customer_employer_state)
    && !isEmpty($data->customer_employer_city)
    && !isEmpty($data->customer_employer_country)
    && !isEmpty($data->customer_employer_doe)
    && !isEmpty($data->customer_account_bvn)
    && !isEmpty($data->customer_account_monthly_salary)
    && !isEmpty($data->customer_account_primary_bank)
) {
// set ha_kyc_employment_status property values

    if (!isEmpty($data->customer_employment_status)) {
        $ha_kyc_employment_status->customer_employment_status = $data->customer_employment_status;
    } else {
        $ha_kyc_employment_status->customer_employment_status = '';
    }
    if (!isEmpty($data->customer_employer_name)) {
        $ha_kyc_employment_status->customer_employer_name = $data->customer_employer_name;
    } else {
        $ha_kyc_employment_status->customer_employer_name = '';
    }
    if (!isEmpty($data->customer_employer_office_number)) {
        $ha_kyc_employment_status->customer_employer_office_number = $data->customer_employer_office_number;
    } else {
        $ha_kyc_employment_status->customer_employer_office_number = '';
    }
    if (!isEmpty($data->customer_employer_address)) {
        $ha_kyc_employment_status->customer_employer_address = $data->customer_employer_address;
    } else {
        $ha_kyc_employment_status->customer_employer_address = '';
    }
    if (!isEmpty($data->customer_employer_nearest_bustop)) {
        $ha_kyc_employment_status->customer_employer_nearest_bustop = $data->customer_employer_nearest_bustop;
    } else {
        $ha_kyc_employment_status->customer_employer_nearest_bustop = '';
    }
    if (!isEmpty($data->customer_employer_state)) {
        $ha_kyc_employment_status->customer_employer_state = $data->customer_employer_state;
    } else {
        $ha_kyc_employment_status->customer_employer_state = '';
    }
    if (!isEmpty($data->customer_employer_city)) {
        $ha_kyc_employment_status->customer_employer_city = $data->customer_employer_city;
    } else {
        $ha_kyc_employment_status->customer_employer_city = '';
    }
    $ha_kyc_employment_status->customer_employer_lga = $data->customer_employer_lga;
    if (!isEmpty($data->customer_employer_country)) {
        $ha_kyc_employment_status->customer_employer_country = $data->customer_employer_country;
    } else {
        $ha_kyc_employment_status->customer_employer_country = '';
    }
    if (!isEmpty($data->customer_employer_doe)) {
        $ha_kyc_employment_status->customer_employer_doe = $data->customer_employer_doe;
    } else {
        $ha_kyc_employment_status->customer_employer_doe = '';
    }
    if (!isEmpty($data->customer_account_bvn)) {
        $ha_kyc_employment_status->customer_account_bvn = $data->customer_account_bvn;
    } else {
        $ha_kyc_employment_status->customer_account_bvn = '';
    }
    if (!isEmpty($data->customer_account_monthly_salary)) {
        $ha_kyc_employment_status->customer_account_monthly_salary = $data->customer_account_monthly_salary;
    } else {
        $ha_kyc_employment_status->customer_account_monthly_salary = '';
    }
    if (!isEmpty($data->customer_account_primary_bank)) {
        $ha_kyc_employment_status->customer_account_primary_bank = $data->customer_account_primary_bank;
    } else {
        $ha_kyc_employment_status->customer_account_primary_bank = '';
    }
    $ha_kyc_employment_status->customer_account_primary_bank_account = $data->customer_account_primary_bank_account;
    $ha_kyc_employment_status->user_id = $profileData->id;
    $ha_kyc_employment_status->follow_up = $data->follow_up;
    $ha_kyc_employment_status->comment = $data->comment;
    $ha_kyc_employment_status->created_at = $data->created_at;
    $ha_kyc_employment_status->updated_at = date('Y-m-d H:m:s');

// update the ha_kyc_employment_status
    if ($ha_kyc_employment_status->update()) {

        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "data" => ""));
    } // if unable to update the ha_kyc_employment_status, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_kyc_employment_status", "data" => ""));

    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_kyc_employment_status. Data is incomplete.", "data" => ""));
}
?>
