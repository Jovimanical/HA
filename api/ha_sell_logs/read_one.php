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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_sell_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_sell_logs object
$ha_sell_logs = new Ha_Sell_Logs($db);
 
// set ID property of record to read
$ha_sell_logs->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_sell_logs to be edited
$ha_sell_logs->readOne();
 
if($ha_sell_logs->id!=null){
    // create array
    $ha_sell_logs_arr = array(
        
"id" => $ha_sell_logs->id,
"seller_id" => $ha_sell_logs->seller_id,
"product_id" => $ha_sell_logs->product_id,
"order_id" => html_entity_decode($ha_sell_logs->order_id),
"qty" => $ha_sell_logs->qty,
"product_price" => $ha_sell_logs->product_price,
"after_commission" => $ha_sell_logs->after_commission,
"created_at" => $ha_sell_logs->created_at,
"updated_at" => $ha_sell_logs->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_sell_logs found","data"=> $ha_sell_logs_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_sell_logs does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_sell_logs does not exist.","data"=> ""));
}
?>
