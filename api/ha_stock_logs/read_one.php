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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_stock_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_stock_logs object
$ha_stock_logs = new Ha_Stock_Logs($db);
 
// set ID property of record to read
$ha_stock_logs->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_stock_logs to be edited
$ha_stock_logs->readOne();
 
if($ha_stock_logs->id!=null){
    // create array
    $ha_stock_logs_arr = array(
        
"id" => $ha_stock_logs->id,
"stock_id" => $ha_stock_logs->stock_id,
"quantity" => $ha_stock_logs->quantity,
"type" => $ha_stock_logs->type,
"created_at" => $ha_stock_logs->created_at,
"updated_at" => $ha_stock_logs->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_stock_logs found","data"=> $ha_stock_logs_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_stock_logs does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_stock_logs does not exist.","data"=> ""));
}
?>
