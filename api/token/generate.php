<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/token.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/jwt/BeforeValidException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/jwt/ExpiredException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/jwt/SignatureInvalidException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/jwt/JWT.php';

use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if (!empty($data->email) && !empty($data->password)) {

    // clean data
    $user_email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $data->password)) {
        $wrongPwdErr = 'Password should be between 6 to 20 characters long, contains at least one special character, lowercase, uppercase and a digit.';
    }


//validate your username and password from database call (copy the code from other generated table files or user table (if you have user or admin table)
    $users = new Ha_Users($db);
    $users->email = $user_email;
    $users->password = password_hash($data->password, PASSWORD_BCRYPT);
    $users->login_validation();

    if ($users->id != null) {

        // Allow only verified user
        if ($users->email_verified == 1 && $users->status == 1 ) {
            $token["data"] = $users;
            $jwt = JWT::encode($token, SECRET_KEY);
            $tokenOutput = array(
                "access_token" => $jwt,
                "expires_in" => $tokenExp,
                "token_type" => "bearer",
            );
            $userData = $users;
            http_response_code(200);
            echo json_encode(array("status" => "success", "code" => 1, "message" => "Login Successful", "token" => $tokenOutput, "user" => $userData));
        } else if($users->status == 0 ) {
            $AccountBarredErr = array(
                "status" => "error",
                "code" => 0,
                "message" => 'Your Account has been deactivated, Please contact support of resolution.',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($AccountBarredErr);
        }else {
            $verificationRequiredErr = array(
                "status" => "error",
                "code" => 0,
                "message" => 'Account verification is required for login.',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($verificationRequiredErr);
        }

    } else {
        http_response_code(400);
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Invalid login.", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to login.", "data" => ""));
}
?>

