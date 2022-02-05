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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_user_logins.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_user_logins object
$ha_user_logins = new Ha_User_Logins($db);
 
// get id of ha_user_logins to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_user_logins to be edited
$ha_user_logins->id = $data->id;

if(
!isEmpty($data->user_id)
){
// set ha_user_logins property values

if(!isEmpty($data->user_id)) { 
$ha_user_logins->user_id = $data->user_id;
} else { 
$ha_user_logins->user_id = '0';
}
$ha_user_logins->user_ip = $data->user_ip;
$ha_user_logins->city = $data->city;
$ha_user_logins->country = $data->country;
$ha_user_logins->country_code = $data->country_code;
$ha_user_logins->longitude = $data->longitude;
$ha_user_logins->latitude = $data->latitude;
$ha_user_logins->browser = $data->browser;
$ha_user_logins->os = $data->os;
$ha_user_logins->created_at = $data->created_at;
$ha_user_logins->updated_at = $data->updated_at;
 
// update the ha_user_logins
if($ha_user_logins->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_user_logins, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_user_logins","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_user_logins. Data is incomplete.","data"=> ""));
}
?>
