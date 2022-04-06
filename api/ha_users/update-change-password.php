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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_users object
$ha_users = new Ha_Users($db);

// get id of ha_users to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_users to be edited
$ha_users->id = $profileData->id;

if (!isEmpty($data->currentPassword) && !isEmpty($data->newPassword) && !isEmpty($data->confirmNewPassword)) {
// set ha_users property values

    if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $data->currentPassword)) {
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
        if ($data->newPassword === $data->confirmNewPassword) {
            $options = [
                'cost' => 12,
            ];
            $ha_users->password = password_hash($data->newPassword, PASSWORD_BCRYPT, $options);
        }else{
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

    $ha_users->password = $data->currentPassword;
    if(!$ha_users->passwordVerification()){
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Current password is not a correct one, please try again',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;

    }

    $ha_users->updated_at = date('Y-m-d H:m:s');

// update the ha_users
    if ($ha_users->changeUserPassword()) {
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Password Updated Successfully", "data" => ""));
    } // if unable to update the ha_users, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to change password", "data" => ""));
    }
} // tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update or change password. Data is incomplete.", "data" => ""));
}
?>
