<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST, PUT, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_accounts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_accounts object
$ha_accounts = new Ha_Accounts($db);

// get id of ha_accounts to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_accounts to be edited
$ha_accounts->id = $data->id;

if (
    !isEmpty($data->user_id)
    && !isEmpty($data->account_number)
    && !isEmpty($data->account_status)
) {
// set ha_accounts property values

    if (!isEmpty($data->user_id)) {
        $ha_accounts->user_id = $data->user_id;
    } else {
        $ha_accounts->user_id = '';
    }
    if (!isEmpty($data->account_number)) {
        $ha_accounts->account_number = $data->account_number;
    } else {
        $ha_accounts->account_number = '';
    }
    if (!isEmpty($data->account_status)) {
        $ha_accounts->account_status = $data->account_status;
    } else {
        $ha_accounts->account_status = 'active';
    }

    $ha_accounts->user_id = $profileData->id;
    $ha_accounts->account_type = $data->account_type;
    $ha_accounts->account_balance = (float) $data->account_balance;
    $ha_accounts->account_point = (int) $data->account_point;
    $ha_accounts->account_blockchain_address = $data->account_blockchain_address;
    $ha_accounts->account_primary = $data->account_primary;
    $ha_accounts->updatedAt = date('Y-m-d H:m:s');

// update the ha_accounts
    if ($ha_accounts->update()) {
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "data" => ""));
    } // if unable to update the ha_accounts, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_accounts", "data" => ""));

    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_accounts. Data is incomplete.", "data" => ""));
}
?>
