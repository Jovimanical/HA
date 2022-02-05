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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_coupons.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_coupons object
$ha_coupons = new Ha_Coupons($db);
 
// get id of ha_coupons to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_coupons to be edited
$ha_coupons->id = $data->id;

if(
!isEmpty($data->coupon_name)
&&!isEmpty($data->coupon_code)
&&!isEmpty($data->discount_type)
&&!isEmpty($data->coupon_amount)
&&!isEmpty($data->status)
){
// set ha_coupons property values

if(!isEmpty($data->coupon_name)) { 
$ha_coupons->coupon_name = $data->coupon_name;
} else { 
$ha_coupons->coupon_name = '';
}
if(!isEmpty($data->coupon_code)) { 
$ha_coupons->coupon_code = $data->coupon_code;
} else { 
$ha_coupons->coupon_code = '';
}
if(!isEmpty($data->discount_type)) { 
$ha_coupons->discount_type = $data->discount_type;
} else { 
$ha_coupons->discount_type = '';
}
if(!isEmpty($data->coupon_amount)) { 
$ha_coupons->coupon_amount = $data->coupon_amount;
} else { 
$ha_coupons->coupon_amount = '0';
}
$ha_coupons->description = $data->description;
$ha_coupons->minimum_spend = $data->minimum_spend;
$ha_coupons->maximum_spend = $data->maximum_spend;
$ha_coupons->usage_limit_per_coupon = $data->usage_limit_per_coupon;
$ha_coupons->usage_limit_per_user = $data->usage_limit_per_user;
if(!isEmpty($data->status)) { 
$ha_coupons->status = $data->status;
} else { 
$ha_coupons->status = '1';
}
$ha_coupons->start_date = $data->start_date;
$ha_coupons->end_date = $data->end_date;
$ha_coupons->created_at = $data->created_at;
$ha_coupons->updated_at = $data->updated_at;
 
// update the ha_coupons
if($ha_coupons->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_coupons, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_coupons","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_coupons. Data is incomplete.","data"=> ""));
}
?>
