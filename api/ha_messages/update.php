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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_messages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_messages object
$ha_messages = new Ha_Messages($db);
 
// get id of ha_messages to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_messages to be edited
$ha_messages->id = $data->id;

if(
!isEmpty($data->user_to)
&&!isEmpty($data->user_from)
&&!isEmpty($data->subject)
&&!isEmpty($data->message)
&&!isEmpty($data->respond)
&&!isEmpty($data->sender_open)
&&!isEmpty($data->receiver_open)
&&!isEmpty($data->sender_delete)
&&!isEmpty($data->receiver_delete)
){
// set ha_messages property values

if(!isEmpty($data->user_to)) { 
$ha_messages->user_to = $data->user_to;
} else { 
$ha_messages->user_to = '';
}
if(!isEmpty($data->user_from)) { 
$ha_messages->user_from = $data->user_from;
} else { 
$ha_messages->user_from = '';
}
if(!isEmpty($data->subject)) { 
$ha_messages->subject = $data->subject;
} else { 
$ha_messages->subject = '';
}
if(!isEmpty($data->message)) { 
$ha_messages->message = $data->message;
} else { 
$ha_messages->message = '';
}
if(!isEmpty($data->respond)) { 
$ha_messages->respond = $data->respond;
} else { 
$ha_messages->respond = '0';
}
if(!isEmpty($data->sender_open)) { 
$ha_messages->sender_open = $data->sender_open;
} else { 
$ha_messages->sender_open = 'y';
}
if(!isEmpty($data->receiver_open)) { 
$ha_messages->receiver_open = $data->receiver_open;
} else { 
$ha_messages->receiver_open = 'n';
}
if(!isEmpty($data->sender_delete)) { 
$ha_messages->sender_delete = $data->sender_delete;
} else { 
$ha_messages->sender_delete = 'n';
}
if(!isEmpty($data->receiver_delete)) { 
$ha_messages->receiver_delete = $data->receiver_delete;
} else { 
$ha_messages->receiver_delete = 'n';
}
 
// update the ha_messages
if($ha_messages->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_messages, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_messages","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_messages. Data is incomplete.","data"=> ""));
}
?>
