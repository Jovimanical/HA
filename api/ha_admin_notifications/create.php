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
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
 
// instantiate ha_admin_notifications object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_admin_notifications.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_admin_notifications = new Ha_Admin_Notifications($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->read_status)){
 
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
 	$lastInsertedId=$ha_admin_notifications->create();
    // create the ha_admin_notifications
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_admin_notifications, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_admin_notifications","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_admin_notifications. Data is incomplete.","data"=> ""));
}
?>
