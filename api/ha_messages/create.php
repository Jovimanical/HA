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
 
// instantiate ha_messages object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_messages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_messages = new Ha_Messages($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->user_to)
&&!isEmpty($data->user_from)
&&!isEmpty($data->subject)
&&!isEmpty($data->message)
&&!isEmpty($data->respond)
&&!isEmpty($data->sender_open)
&&!isEmpty($data->receiver_open)
&&!isEmpty($data->sender_delete)
&&!isEmpty($data->receiver_delete)){
 
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
 	$lastInsertedId=$ha_messages->create();
    // create the ha_messages
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_messages, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_messages","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_messages. Data is incomplete.","data"=> ""));
}
?>
