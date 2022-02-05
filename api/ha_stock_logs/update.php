<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
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
 
// get id of ha_stock_logs to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_stock_logs to be edited
$ha_stock_logs->id = $data->id;

if(
!isEmpty($data->stock_id)
&&!isEmpty($data->quantity)
&&!isEmpty($data->type)
){
// set ha_stock_logs property values

if(!isEmpty($data->stock_id)) { 
$ha_stock_logs->stock_id = $data->stock_id;
} else { 
$ha_stock_logs->stock_id = '';
}
if(!isEmpty($data->quantity)) { 
$ha_stock_logs->quantity = $data->quantity;
} else { 
$ha_stock_logs->quantity = '';
}
if(!isEmpty($data->type)) { 
$ha_stock_logs->type = $data->type;
} else { 
$ha_stock_logs->type = '';
}
$ha_stock_logs->created_at = $data->created_at;
$ha_stock_logs->updated_at = $data->updated_at;
 
// update the ha_stock_logs
if($ha_stock_logs->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_stock_logs, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_stock_logs","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_stock_logs. Data is incomplete.","data"=> ""));
}
?>
