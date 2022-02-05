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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_tickets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_support_tickets object
$ha_support_tickets = new Ha_Support_Tickets($db);
 
// get id of ha_support_tickets to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_support_tickets to be edited
$ha_support_tickets->id = $data->id;

if(
!isEmpty($data->status)
&&!isEmpty($data->priority)
){
// set ha_support_tickets property values

$ha_support_tickets->user_id = $data->user_id;
$ha_support_tickets->seller_id = $data->seller_id;
$ha_support_tickets->name = $data->name;
$ha_support_tickets->email = $data->email;
$ha_support_tickets->ticket = $data->ticket;
$ha_support_tickets->subject = $data->subject;
if(!isEmpty($data->status)) { 
$ha_support_tickets->status = $data->status;
} else { 
$ha_support_tickets->status = '';
}
if(!isEmpty($data->priority)) { 
$ha_support_tickets->priority = $data->priority;
} else { 
$ha_support_tickets->priority = '0';
}
$ha_support_tickets->last_reply = $data->last_reply;
$ha_support_tickets->created_at = $data->created_at;
$ha_support_tickets->updated_at = $data->updated_at;
 
// update the ha_support_tickets
if($ha_support_tickets->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_support_tickets, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_support_tickets","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_support_tickets. Data is incomplete.","data"=> ""));
}
?>
