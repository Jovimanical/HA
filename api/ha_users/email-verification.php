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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_users object
$ha_users = new Ha_Users($db);

// get id of ha_users to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

if (!isEmpty($data->email) && !isEmpty($data->code)) {
// set ha_users property values

    $email = $data->email;
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid email address please type a valid email address!',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    }

    $ha_users->email = $email;

    if (!$ha_users->emailExits()) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'No user is registered with this email address!',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;

    }

    $ha_users->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $ha_users->verification_code = filter_var($data->code, FILTER_SANITIZE_STRING);
    $ha_users->email_verified = 1;



// update the ha_users
    if ($ha_users->isValidVerificationToken()) {

        if ($ha_users->activateUser()) {
            // set response code - 200 ok
            http_response_code(200);
            // tell the user
            echo json_encode(array("status" => "success", "code" => 1, "message" => "Email verified and User Activated", "data" => ""));
        } else {
            // set response code - 503 service unavailable
            http_response_code(503);
            // tell the user
            echo json_encode(array("status" => "error", "code" => 0, "message" => "Email Verified but User not Activated, please try again or contact admin", "data" => ""));

        }
    } // if unable to update the ha_users, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_users", "data" => ""));
    }
} // tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_users. Data is incomplete.", "data" => ""));
}
?>
