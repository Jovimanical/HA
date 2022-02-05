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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_email_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_email_logs object
$ha_email_logs = new Ha_Email_Logs($db);
 
// get id of ha_email_logs to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_email_logs to be edited
$ha_email_logs->id = $data->id;

if(
true
){
// set ha_email_logs property values

$ha_email_logs->user_id = $data->user_id;
$ha_email_logs->seller_id = $data->seller_id;
$ha_email_logs->mail_sender = $data->mail_sender;
$ha_email_logs->email_from = $data->email_from;
$ha_email_logs->email_to = $data->email_to;
$ha_email_logs->subject = $data->subject;
$ha_email_logs->message = $data->message;
$ha_email_logs->created_at = $data->created_at;
$ha_email_logs->updated_at = $data->updated_at;
 
// update the ha_email_logs
if($ha_email_logs->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_email_logs, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_email_logs","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_email_logs. Data is incomplete.","data"=> ""));
}
?>
