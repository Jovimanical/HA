<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: PUT, POST, OPTIONS');
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_customer_liabilities.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_customer_liabilities object
$ha_customer_liabilities = new Ha_Customer_Liabilities($db);

// get id of ha_customer_liabilities to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_customer_liabilities to be edited
$ha_customer_liabilities->id = $data->id;

if (
    !isEmpty($data->user_id)
    && !isEmpty($data->accountNumber)
    && !isEmpty($data->balance)
    && !isEmpty($data->description)
    && !isEmpty($data->monthlyPayment)
) {
// set ha_customer_liabilities property values

    if (!isEmpty($data->user_id)) {
        $ha_customer_liabilities->user_id = $data->user_id;
    } else {
        $ha_customer_liabilities->user_id = '';
    }
    if (!isEmpty($data->accountNumber)) {
        $ha_customer_liabilities->accountNumber = $data->accountNumber;
    } else {
        $ha_customer_liabilities->accountNumber = '';
    }
    if (!isEmpty($data->balance)) {
        $ha_customer_liabilities->balance = $data->balance;
    } else {
        $ha_customer_liabilities->balance = '0.00';
    }
    if (!isEmpty($data->description)) {
        $ha_customer_liabilities->description = $data->description;
    } else {
        $ha_customer_liabilities->description = '';
    }
    if (!isEmpty($data->monthlyPayment)) {
        $ha_customer_liabilities->monthlyPayment = $data->monthlyPayment;
    } else {
        $ha_customer_liabilities->monthlyPayment = '0.00';
    }
    $ha_customer_liabilities->liability_status = $data->liability_status;
    $ha_customer_liabilities->updatedAt = $data->updatedAt;

// update the ha_customer_liabilities
    if ($ha_customer_liabilities->update()) {

        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "document" => ""));
    } // if unable to update the ha_customer_liabilities, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_customer_liabilities", "document" => ""));

    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_customer_liabilities. Data is incomplete.", "document" => ""));
}
?>
