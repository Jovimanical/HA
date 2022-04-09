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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_password_resets.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_password_resets object
$ha_users = new Ha_Users($db);
$ha_password_resets = new Ha_Password_Resets($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


if (!isEmpty($data->newPassword) && !isEmpty($data->confirmNewPassword) && !isEmpty($data->email)) {

    $newPassword = filter_var($data->newPassword, FILTER_SANITIZE_STRING);
    $confirmNewPassword = filter_var($data->confirmNewPassword, FILTER_SANITIZE_STRING);
    $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);


    if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $newPassword)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Password should be between 6 and 20 characters long, contains at least one special character, lowercase, uppercase and a digit.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        if ($newPassword === $confirmNewPassword) {
            $options = [
                'cost' => 12,
            ];
            $ha_users->password = password_hash($newPassword, PASSWORD_BCRYPT, $options);
        } else {
            $error_message = array(
                "status" => "error",
                "code" => 0,
                "message" => 'Password Mismatch, Please try Again.',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($error_message);
            return;
        }
    }

    $ha_users->email = $email;
    $ha_password_resets->email = $email;
    // read the details of ha_password_resets to be edited

    if ($ha_users->resetUserPassword()) {

        $ha_password_resets->delete();
        // set response code - 200 OK
        http_response_code(200);
        // make it json format
        echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_password_resets found", "forgotPasswordStep" => 3));

    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user ha_password_resets does not exist
        echo json_encode(array("status" => "error", "code" => 0, "message" => "ha_password_resets does not exist.", "data" => ""));
    }

} else {
    // set response code - 404 Not found
    http_response_code(501);

    // tell the user ha_password_resets does not exist
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Invalid input parameters does not exist.", "data" => ""));
}
?>
