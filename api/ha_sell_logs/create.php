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
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
 
// instantiate ha_sell_logs object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_sell_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_sell_logs = new Ha_Sell_Logs($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->seller_id)
&&!isEmpty($data->product_id)
&&!isEmpty($data->order_id)
&&!isEmpty($data->qty)
&&!isEmpty($data->product_price)
&&!isEmpty($data->after_commission)){
 
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
 	$lastInsertedId=$ha_sell_logs->create();
    // create the ha_sell_logs
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_sell_logs, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_sell_logs","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_sell_logs. Data is incomplete.","data"=> ""));
}
?>
