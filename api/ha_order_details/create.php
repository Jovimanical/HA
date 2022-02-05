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
 
// instantiate ha_order_details object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_order_details.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_order_details = new Ha_Order_Details($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->seller_id)
&&!isEmpty($data->order_id)
&&!isEmpty($data->product_id)
&&!isEmpty($data->quantity)
&&!isEmpty($data->base_price)
&&!isEmpty($data->total_price)
&&!isEmpty($data->details)){
 
    // set ha_order_details property values
	 
if(!isEmpty($data->seller_id)) { 
$ha_order_details->seller_id = $data->seller_id;
} else { 
$ha_order_details->seller_id = '0';
}
if(!isEmpty($data->order_id)) { 
$ha_order_details->order_id = $data->order_id;
} else { 
$ha_order_details->order_id = '';
}
if(!isEmpty($data->product_id)) { 
$ha_order_details->product_id = $data->product_id;
} else { 
$ha_order_details->product_id = '';
}
if(!isEmpty($data->quantity)) { 
$ha_order_details->quantity = $data->quantity;
} else { 
$ha_order_details->quantity = '';
}
if(!isEmpty($data->base_price)) { 
$ha_order_details->base_price = $data->base_price;
} else { 
$ha_order_details->base_price = '0.00';
}
if(!isEmpty($data->total_price)) { 
$ha_order_details->total_price = $data->total_price;
} else { 
$ha_order_details->total_price = '0.00';
}
if(!isEmpty($data->details)) { 
$ha_order_details->details = $data->details;
} else { 
$ha_order_details->details = '';
}
$ha_order_details->created_at = $data->created_at;
$ha_order_details->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_order_details->create();
    // create the ha_order_details
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_order_details, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_order_details","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_order_details. Data is incomplete.","data"=> ""));
}
?>
