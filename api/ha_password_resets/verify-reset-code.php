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
        header("Access-Control-Allow-Methods: POST, OPTIONS");
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_password_resets.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_password_resets object
$ha_password_resets = new Ha_Password_Resets($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


if (!isEmpty($data->email) && !isEmpty($data->verificationCode)) {

    $ha_password_resets->token = filter_var($data->verificationCode, FILTER_SANITIZE_STRING);
    $ha_password_resets->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $curDate = date("Y-m-d H:i:s");

    // read the details of ha_password_resets to be edited
    $ha_password_resets->readOne();

    if ($ha_password_resets->id != null) {

        $expDate = $ha_password_resets->expDate;
        if ($expDate >= $curDate) {
            // set response code - 200 OK
            http_response_code(200);
            // make it json format
            echo json_encode(array("status" => "success", "code" => 1, "email" => $data->email, "forgotPasswordStep" => 3));
        } else {
            http_response_code(404);
            // make it json format
            echo json_encode(array("status" => "error", "code" => 0, "message" => "The link is expired. You are trying to use the expired link which 
as valid only 24 hours (1 days after request).", "forgotPasswordStep" => 4, "data" => ""));
        }
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user ha_password_resets does not exist
        echo json_encode(array("status" => "error", "code" => 0, "forgotPasswordStep" => 4, "message" => "The link is invalid/expired. Either you did not copy the correct link
from the email, or you have already used the key in which case it is 
deactivated.", "document" => ""));
    }

} else {
    // set response code - 404 Not found
    http_response_code(501);

    // tell the user ha_password_resets does not exist
    echo json_encode(array("status" => "error", "code" => 0, "forgotPasswordStep" => 4, "message" => "Invalid input parameters does not exist.", "document" => ""));
}
?>
