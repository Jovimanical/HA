<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
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
$data = json_decode(file_get_contents("php://input"));

// set ID property of ha_users to be edited
$ha_users->id = $data->id;

if (
    !isEmpty($data->username)
    && !isEmpty($data->email)
    && !isEmpty($data->password)
    && !isEmpty($data->status)
    && !isEmpty($data->email_verified)
    && !isEmpty($data->sms_verified)
    && !isEmpty($data->two_factor_status)
    && !isEmpty($data->two_factor_verified)
) {
// set ha_users property values

    $ha_users->firstname = $data->firstname;
    $ha_users->lastname = $data->lastname;
    if (!isEmpty($data->username)) {
        $ha_users->username = $data->username;
    } else {
        $ha_users->username = '';
    }
    if (!isEmpty($data->email)) {
        $ha_users->email = $data->email;
    } else {
        $ha_users->email = '';
    }
    $ha_users->country_code = $data->country_code;
    $ha_users->mobile = $data->mobile;
    if (!isEmpty($data->password)) {
        $ha_users->password = $data->password;
    } else {
        $ha_users->password = '';
    }
    $ha_users->profileImage = $data->profileImage;
    $ha_users->address = $data->address;
    if (!isEmpty($data->status)) {
        $ha_users->status = $data->status;
    } else {
        $ha_users->status = '1';
    }
    if (!isEmpty($data->email_verified)) {
        $ha_users->email_verified = $data->email_verified;
    } else {
        $ha_users->email_verified = '0';
    }
    if (!isEmpty($data->sms_verified)) {
        $ha_users->sms_verified = $data->sms_verified;
    } else {
        $ha_users->sms_verified = '0';
    }
    $ha_users->verification_code = $data->verification_code;
    $ha_users->verification_code_send_at = $data->verification_code_send_at;
    if (!isEmpty($data->two_factor_status)) {
        $ha_users->two_factor_status = $data->two_factor_status;
    } else {
        $ha_users->two_factor_status = '0';
    }
    if (!isEmpty($data->two_factor_verified)) {
        $ha_users->two_factor_verified = $data->two_factor_verified;
    } else {
        $ha_users->two_factor_verified = '1';
    }
    $ha_users->roles = $data->roles;
    $ha_users->remember_token = $data->remember_token;
    $ha_users->created_at = $data->created_at;
    $ha_users->updated_at = $data->updated_at;

// update the ha_users
    if ($ha_users->update()) {

        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "data" => ""));
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
