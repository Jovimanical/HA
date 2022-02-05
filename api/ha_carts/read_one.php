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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_carts.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_carts object
$ha_carts = new Ha_Carts($db);
 
// set ID property of record to read
$ha_carts->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_carts to be edited
$ha_carts->readOne();
 
if($ha_carts->id!=null){
    // create array
    $ha_carts_arr = array(
        
"id" => $ha_carts->id,
"user_id" => $ha_carts->user_id,
"session_id" => html_entity_decode($ha_carts->session_id),
"product_id" => $ha_carts->product_id,
"attributes" => $ha_carts->attributes,
"quantity" => $ha_carts->quantity,
"created_at" => $ha_carts->created_at,
"updated_at" => $ha_carts->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_carts found","data"=> $ha_carts_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_carts does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_carts does not exist.","data"=> ""));
}
?>
