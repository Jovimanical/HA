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
 
// instantiate ha_support_messages object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_messages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_support_messages = new Ha_Support_Messages($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->supportticket_id)
&&!isEmpty($data->admin_id)
&&!isEmpty($data->message)){
 
    // set ha_support_messages property values
	 
if(!isEmpty($data->supportticket_id)) { 
$ha_support_messages->supportticket_id = $data->supportticket_id;
} else { 
$ha_support_messages->supportticket_id = '0';
}
if(!isEmpty($data->admin_id)) { 
$ha_support_messages->admin_id = $data->admin_id;
} else { 
$ha_support_messages->admin_id = '0';
}
if(!isEmpty($data->message)) { 
$ha_support_messages->message = $data->message;
} else { 
$ha_support_messages->message = '';
}
$ha_support_messages->created_at = $data->created_at;
$ha_support_messages->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_support_messages->create();
    // create the ha_support_messages
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_support_messages, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_support_messages","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_support_messages. Data is incomplete.","data"=> ""));
}
?>
