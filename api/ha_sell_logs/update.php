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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_sell_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_sell_logs object
$ha_sell_logs = new Ha_Sell_Logs($db);
 
// get id of ha_sell_logs to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_sell_logs to be edited
$ha_sell_logs->id = $data->id;

if(
!isEmpty($data->seller_id)
&&!isEmpty($data->product_id)
&&!isEmpty($data->order_id)
&&!isEmpty($data->qty)
&&!isEmpty($data->product_price)
&&!isEmpty($data->after_commission)
){
// set ha_sell_logs property values

if(!isEmpty($data->seller_id)) { 
$ha_sell_logs->seller_id = $data->seller_id;
} else { 
$ha_sell_logs->seller_id = '';
}
if(!isEmpty($data->product_id)) { 
$ha_sell_logs->product_id = $data->product_id;
} else { 
$ha_sell_logs->product_id = '';
}
if(!isEmpty($data->order_id)) { 
$ha_sell_logs->order_id = $data->order_id;
} else { 
$ha_sell_logs->order_id = '';
}
if(!isEmpty($data->qty)) { 
$ha_sell_logs->qty = $data->qty;
} else { 
$ha_sell_logs->qty = '';
}
if(!isEmpty($data->product_price)) { 
$ha_sell_logs->product_price = $data->product_price;
} else { 
$ha_sell_logs->product_price = '';
}
if(!isEmpty($data->after_commission)) { 
$ha_sell_logs->after_commission = $data->after_commission;
} else { 
$ha_sell_logs->after_commission = '';
}
$ha_sell_logs->created_at = $data->created_at;
$ha_sell_logs->updated_at = $data->updated_at;
 
// update the ha_sell_logs
if($ha_sell_logs->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_sell_logs, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_sell_logs","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_sell_logs. Data is incomplete.","data"=> ""));
}
?>
