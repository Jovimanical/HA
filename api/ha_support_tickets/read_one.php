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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_tickets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_support_tickets object
$ha_support_tickets = new Ha_Support_Tickets($db);
 
// set ID property of record to read
$ha_support_tickets->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_support_tickets to be edited
$ha_support_tickets->readOne();
 
if($ha_support_tickets->id!=null){
    // create array
    $ha_support_tickets_arr = array(
        
"id" => $ha_support_tickets->id,
"user_id" => $ha_support_tickets->user_id,
"seller_id" => $ha_support_tickets->seller_id,
"name" => $ha_support_tickets->name,
"email" => $ha_support_tickets->email,
"ticket" => $ha_support_tickets->ticket,
"subject" => html_entity_decode($ha_support_tickets->subject),
"status" => $ha_support_tickets->status,
"priority" => $ha_support_tickets->priority,
"last_reply" => $ha_support_tickets->last_reply,
"created_at" => $ha_support_tickets->created_at,
"updated_at" => $ha_support_tickets->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_support_tickets found","data"=> $ha_support_tickets_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_support_tickets does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_support_tickets does not exist.","data"=> ""));
}
?>
