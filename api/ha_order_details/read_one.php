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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_order_details.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_order_details object
$ha_order_details = new Ha_Order_Details($db);
 
// set ID property of record to read
$ha_order_details->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_order_details to be edited
$ha_order_details->readOne();
 
if($ha_order_details->id!=null){
    // create array
    $ha_order_details_arr = array(
        
"id" => $ha_order_details->id,
"seller_id" => $ha_order_details->seller_id,
"order_id" => $ha_order_details->order_id,
"product_id" => $ha_order_details->product_id,
"quantity" => $ha_order_details->quantity,
"base_price" => $ha_order_details->base_price,
"total_price" => $ha_order_details->total_price,
"details" => $ha_order_details->details,
"created_at" => $ha_order_details->created_at,
"updated_at" => $ha_order_details->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_order_details found","data"=> $ha_order_details_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_order_details does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_order_details does not exist.","data"=> ""));
}
?>
