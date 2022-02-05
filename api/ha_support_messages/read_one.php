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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_messages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_support_messages object
$ha_support_messages = new Ha_Support_Messages($db);
 
// set ID property of record to read
$ha_support_messages->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_support_messages to be edited
$ha_support_messages->readOne();
 
if($ha_support_messages->id!=null){
    // create array
    $ha_support_messages_arr = array(
        
"id" => $ha_support_messages->id,
"supportticket_id" => $ha_support_messages->supportticket_id,
"admin_id" => $ha_support_messages->admin_id,
"message" => $ha_support_messages->message,
"created_at" => $ha_support_messages->created_at,
"updated_at" => $ha_support_messages->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_support_messages found","data"=> $ha_support_messages_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_support_messages does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_support_messages does not exist.","data"=> ""));
}
?>
