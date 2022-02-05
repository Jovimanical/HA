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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_admin_notifications.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_admin_notifications object
$ha_admin_notifications = new Ha_Admin_Notifications($db);
 
// get id of ha_admin_notifications to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_admin_notifications to be edited
$ha_admin_notifications->id = $data->id;

if(
!isEmpty($data->read_status)
){
// set ha_admin_notifications property values

$ha_admin_notifications->user_id = $data->user_id;
$ha_admin_notifications->seller_id = $data->seller_id;
$ha_admin_notifications->title = $data->title;
if(!isEmpty($data->read_status)) { 
$ha_admin_notifications->read_status = $data->read_status;
} else { 
$ha_admin_notifications->read_status = '0';
}
$ha_admin_notifications->click_url = $data->click_url;
$ha_admin_notifications->created_at = $data->created_at;
$ha_admin_notifications->updated_at = $data->updated_at;
 
// update the ha_admin_notifications
if($ha_admin_notifications->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_admin_notifications, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_admin_notifications","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_admin_notifications. Data is incomplete.","data"=> ""));
}
?>
