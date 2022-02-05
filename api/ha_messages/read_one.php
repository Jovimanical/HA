<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
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
 
// set ID property of record to read
$ha_messages->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_messages to be edited
$ha_messages->readOne();
 
if($ha_messages->id!=null){
    // create array
    $ha_messages_arr = array(
        
"id" => $ha_messages->id,
"user_to" => $ha_messages->user_to,
"user_from" => $ha_messages->user_from,
"subject" => $ha_messages->subject,
"message" => $ha_messages->message,
"respond" => $ha_messages->respond,
"sender_open" => $ha_messages->sender_open,
"receiver_open" => $ha_messages->receiver_open,
"sender_delete" => $ha_messages->sender_delete,
"receiver_delete" => $ha_messages->receiver_delete
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_messages found","data"=> $ha_messages_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_messages does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_messages does not exist.","data"=> ""));
}
?>
