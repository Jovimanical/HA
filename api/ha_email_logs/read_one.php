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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_email_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_email_logs object
$ha_email_logs = new Ha_Email_Logs($db);
 
// set ID property of record to read
$ha_email_logs->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_email_logs to be edited
$ha_email_logs->readOne();
 
if($ha_email_logs->id!=null){
    // create array
    $ha_email_logs_arr = array(
        
"id" => $ha_email_logs->id,
"user_id" => $ha_email_logs->user_id,
"seller_id" => $ha_email_logs->seller_id,
"mail_sender" => $ha_email_logs->mail_sender,
"email_from" => $ha_email_logs->email_from,
"email_to" => $ha_email_logs->email_to,
"subject" => html_entity_decode($ha_email_logs->subject),
"message" => $ha_email_logs->message,
"created_at" => $ha_email_logs->created_at,
"updated_at" => $ha_email_logs->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_email_logs found","data"=> $ha_email_logs_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_email_logs does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_email_logs does not exist.","data"=> ""));
}
?>
