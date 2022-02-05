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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_kycs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_kycs object
$ha_kycs = new Ha_Kycs($db);
 
// set ID property of record to read
$ha_kycs->ERROR_NOPRIMARYKEYFOUND = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_kycs to be edited
$ha_kycs->readOne();
 
if($ha_kycs->ERROR_NOPRIMARYKEYFOUND!=null){
    // create array
    $ha_kycs_arr = array(
        
"id" => $ha_kycs->id,
"first_name" => html_entity_decode($ha_kycs->first_name),
"last_name" => html_entity_decode($ha_kycs->last_name),
"city" => html_entity_decode($ha_kycs->city),
"country" => html_entity_decode($ha_kycs->country),
"city_of_birth" => html_entity_decode($ha_kycs->city_of_birth),
"country_of_birth" => html_entity_decode($ha_kycs->country_of_birth),
"nationality" => html_entity_decode($ha_kycs->nationality),
"document_type" => $ha_kycs->document_type,
"document_number" => html_entity_decode($ha_kycs->document_number),
"issuing_authority" => html_entity_decode($ha_kycs->issuing_authority),
"issue_on" => $ha_kycs->issue_on,
"valid_until" => $ha_kycs->valid_until,
"order_amount" => $ha_kycs->order_amount,
"internal" => $ha_kycs->internal,
"external" => $ha_kycs->external,
"follow_up" => $ha_kycs->follow_up,
"comment" => $ha_kycs->comment,
"created_at" => $ha_kycs->created_at,
"updated_at" => $ha_kycs->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_kycs found","data"=> $ha_kycs_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_kycs does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_kycs does not exist.","data"=> ""));
}
?>
