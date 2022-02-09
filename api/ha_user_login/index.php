<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST, OPTIONS');
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header('Access-Control-Allow-Credentials: true');
    header("HTTP/1.1 200 OK");
    return;
}

require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/token.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_user_logins.php';


use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();
$ha_user_logins = new Ha_User_Logins($db);

$USER_IP = getIpAddress();
$ua = getBrowser();

// get posted data
$data = json_decode(file_get_contents("php://input"));

$whitelist = array('127.0.0.1', "::1");
$getInfo = null;
if (!in_array($USER_IP, $whitelist) && !filter_var($USER_IP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
    //call api
    $RETURNED_URL = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $USER_IP);
    //decode json data
    $getInfo = json_decode($RETURNED_URL);
}

//print_r(json_encode($_POST));

try {
// make sure data is not empty
    if (!empty($data->email) && !empty($data->password)) {

        // clean data
        if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            $error_message = array(
                "status" => "error",
                "code" => 0,
                "message" => 'Invalid Email! Email format is invalid.',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($error_message);
            return;
        } else {
            $user_email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
        }


        if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $data->password)) {
            $error_message = array(
                "status" => "error",
                "code" => 0,
                "message" => 'Password should be between 6 and 20 characters long, contains at least one special character, lowercase, uppercase and a digit.',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($error_message);
            return;

        }


//validate your username and password from database call (copy the code from other generated table files or user table (if you have user or admin table)
        $users = new Ha_Users($db);
        $users->email = $user_email;
        $users->password = $data->password;
        $users->login_validation();

        if ($users->id != null) {

            // Allow only verified user
            if ($users->status == 1 && $users->email_verified == 1) {

                unset($users->password);
                unset($users->verification_code);
                unset($users->verification_code_send_at);
                unset($users->two_factor_verified);
                unset($users->updated_at);
                unset($users->no_of_records_per_page);
                unset($users->pageNo);

                $token["data"] = $users;
                $jwt = JWT::encode($token, SECRET_KEY, ALGORITHM);
                $tokenOutput = array(
                    "access_token" => $jwt,
                    "expires_in" => $tokenExp,
                    "token_type" => "bearer",
                );
                $userData = $users;
                http_response_code(200);
                echo json_encode(array("status" => "success", "code" => 1, "message" => "Login Successful", "token" => $tokenOutput, "user" => $userData));
            } else if ($users->status == 0 && $users->email_verified == 1) {
                $AccountBarredErr = array(
                    "status" => "error",
                    "code" => 0,
                    "message" => 'Your Account has been deactivated, Please contact support of resolution.',
                    "time" => date('Y-m-d')
                );
                http_response_code(400);
                echo json_encode($AccountBarredErr);
            } else {
                $verificationRequiredErr = array(
                    "status" => "error",
                    "code" => 0,
                    "message" => 'Account verification is required for login.',
                    "time" => date('Y-m-d')
                );
                http_response_code(400);
                echo json_encode($verificationRequiredErr);
            }

            $ha_user_logins->user_id = $users->id;
            $ha_user_logins->user_ip = $USER_IP;
            $ha_user_logins->city = $getInfo === null ? 'UNKNOWN' : $getInfo->geoplugin_city || 'UNKNOWN';
            $ha_user_logins->country = $getInfo === null ? 'UNKNOWN' : $getInfo->geoplugin_countryName || 'UNKNOWN';
            $ha_user_logins->country_code = $getInfo === null ? 'UNKNOWN' : $getInfo->geoplugin_countryCode || 'UNKNOWN';
            $ha_user_logins->longitude = $getInfo === null ? 'UNKNOWN' : $getInfo->geoplugin_latitude || 'UNKNOWN';
            $ha_user_logins->latitude = $getInfo === null ? 'UNKNOWN' : $getInfo->geoplugin_longitude || 'UNKNOWN';
            $ha_user_logins->browser = "browser info: " . $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . " reports: <br >" . $ua['userAgent'];
            $ha_user_logins->os = $ua['platform'] || 'Unknown';
            $ha_user_logins->created_at = date('Y-m-d H:m:s');
            $ha_user_logins->updated_at = date('Y-m-d H:m:s');
            $ha_user_logins->create();

        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "code" => 0, "message" => "Invalid login information, please try again.", "data" => null));
        }
    } else {

        // set response code - 400 bad request
        http_response_code(400);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to login", "data" => $data));
    }
} catch (Exception $e) {
    echo $e->getMessage();
}


?>

