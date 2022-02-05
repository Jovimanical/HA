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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_support_attachments.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_support_attachments object
$ha_support_attachments = new Ha_Support_Attachments($db);
 
// set ID property of record to read
$ha_support_attachments->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_support_attachments to be edited
$ha_support_attachments->readOne();
 
if($ha_support_attachments->id!=null){
    // create array
    $ha_support_attachments_arr = array(
        
"id" => $ha_support_attachments->id,
"support_message_id" => $ha_support_attachments->support_message_id,
"attachment" => html_entity_decode($ha_support_attachments->attachment),
"created_at" => $ha_support_attachments->created_at,
"updated_at" => $ha_support_attachments->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_support_attachments found","data"=> $ha_support_attachments_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_support_attachments does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_support_attachments does not exist.","data"=> ""));
}
?>
