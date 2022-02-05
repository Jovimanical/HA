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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_attachments.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_support_attachments object
$ha_support_attachments = new Ha_Support_Attachments($db);
 
// get id of ha_support_attachments to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_support_attachments to be edited
$ha_support_attachments->id = $data->id;

if(
!isEmpty($data->support_message_id)
&&!isEmpty($data->attachment)
){
// set ha_support_attachments property values

if(!isEmpty($data->support_message_id)) { 
$ha_support_attachments->support_message_id = $data->support_message_id;
} else { 
$ha_support_attachments->support_message_id = '';
}
if(!isEmpty($data->attachment)) { 
$ha_support_attachments->attachment = $data->attachment;
} else { 
$ha_support_attachments->attachment = '';
}
$ha_support_attachments->created_at = $data->created_at;
$ha_support_attachments->updated_at = $data->updated_at;
 
// update the ha_support_attachments
if($ha_support_attachments->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_support_attachments, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_support_attachments","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_support_attachments. Data is incomplete.","data"=> ""));
}
?>
