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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_orders.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_orders object
$ha_orders = new Ha_Orders($db);
 
// get id of ha_orders to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_orders to be edited
$ha_orders->id = $data->id;

if(
!isEmpty($data->order_number)
&&!isEmpty($data->user_id)
&&!isEmpty($data->shipping_address)
&&!isEmpty($data->shipping_method_id)
&&!isEmpty($data->shipping_charge)
&&!isEmpty($data->coupon_code)
&&!isEmpty($data->coupon_amount)
&&!isEmpty($data->total_amount)
&&!isEmpty($data->order_type)
&&!isEmpty($data->payment_status)
&&!isEmpty($data->status)
){
// set ha_orders property values

if(!isEmpty($data->order_number)) { 
$ha_orders->order_number = $data->order_number;
} else { 
$ha_orders->order_number = '';
}
if(!isEmpty($data->user_id)) { 
$ha_orders->user_id = $data->user_id;
} else { 
$ha_orders->user_id = '';
}
if(!isEmpty($data->shipping_address)) { 
$ha_orders->shipping_address = $data->shipping_address;
} else { 
$ha_orders->shipping_address = '';
}
if(!isEmpty($data->shipping_method_id)) { 
$ha_orders->shipping_method_id = $data->shipping_method_id;
} else { 
$ha_orders->shipping_method_id = '';
}
if(!isEmpty($data->shipping_charge)) { 
$ha_orders->shipping_charge = $data->shipping_charge;
} else { 
$ha_orders->shipping_charge = '0.00';
}
if(!isEmpty($data->coupon_code)) { 
$ha_orders->coupon_code = $data->coupon_code;
} else { 
$ha_orders->coupon_code = '';
}
if(!isEmpty($data->coupon_amount)) { 
$ha_orders->coupon_amount = $data->coupon_amount;
} else { 
$ha_orders->coupon_amount = '0';
}
if(!isEmpty($data->total_amount)) { 
$ha_orders->total_amount = $data->total_amount;
} else { 
$ha_orders->total_amount = '0.00';
}
if(!isEmpty($data->order_type)) { 
$ha_orders->order_type = $data->order_type;
} else { 
$ha_orders->order_type = '';
}
if(!isEmpty($data->payment_status)) { 
$ha_orders->payment_status = $data->payment_status;
} else { 
$ha_orders->payment_status = '0';
}
if(!isEmpty($data->status)) { 
$ha_orders->status = $data->status;
} else { 
$ha_orders->status = '0';
}
$ha_orders->created_at = $data->created_at;
$ha_orders->updated_at = $data->updated_at;
 
// update the ha_orders
if($ha_orders->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_orders, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_orders","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_orders. Data is incomplete.","data"=> ""));
}
?>
