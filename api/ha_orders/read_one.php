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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_orders.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_orders object
$ha_orders = new Ha_Orders($db);
 
// set ID property of record to read
$ha_orders->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_orders to be edited
$ha_orders->readOne();
 
if($ha_orders->id!=null){
    // create array
    $ha_orders_arr = array(
        
"id" => $ha_orders->id,
"order_number" => html_entity_decode($ha_orders->order_number),
"user_id" => $ha_orders->user_id,
"shipping_address" => $ha_orders->shipping_address,
"shipping_method_id" => $ha_orders->shipping_method_id,
"shipping_charge" => $ha_orders->shipping_charge,
"coupon_code" => $ha_orders->coupon_code,
"coupon_amount" => $ha_orders->coupon_amount,
"total_amount" => $ha_orders->total_amount,
"order_type" => $ha_orders->order_type,
"payment_status" => $ha_orders->payment_status,
"status" => $ha_orders->status,
"created_at" => $ha_orders->created_at,
"updated_at" => $ha_orders->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_orders found","data"=> $ha_orders_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_orders does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_orders does not exist.","data"=> ""));
}
?>
