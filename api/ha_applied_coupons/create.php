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
 
// instantiate ha_applied_coupons object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_applied_coupons.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_applied_coupons = new Ha_Applied_Coupons($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->user_id)
&&!isEmpty($data->coupon_id)
&&!isEmpty($data->order_id)
&&!isEmpty($data->amount)){
 
    // set ha_applied_coupons property values
	 
if(!isEmpty($data->user_id)) { 
$ha_applied_coupons->user_id = $data->user_id;
} else { 
$ha_applied_coupons->user_id = '';
}
if(!isEmpty($data->coupon_id)) { 
$ha_applied_coupons->coupon_id = $data->coupon_id;
} else { 
$ha_applied_coupons->coupon_id = '';
}
if(!isEmpty($data->order_id)) { 
$ha_applied_coupons->order_id = $data->order_id;
} else { 
$ha_applied_coupons->order_id = '';
}
if(!isEmpty($data->amount)) { 
$ha_applied_coupons->amount = $data->amount;
} else { 
$ha_applied_coupons->amount = '';
}
$ha_applied_coupons->created_at = $data->created_at;
$ha_applied_coupons->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_applied_coupons->create();
    // create the ha_applied_coupons
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_applied_coupons, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_applied_coupons","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_applied_coupons. Data is incomplete.","data"=> ""));
}
?>
