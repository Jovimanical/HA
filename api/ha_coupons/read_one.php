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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_coupons.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_coupons object
$ha_coupons = new Ha_Coupons($db);
 
// set ID property of record to read
$ha_coupons->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_coupons to be edited
$ha_coupons->readOne();
 
if($ha_coupons->id!=null){
    // create array
    $ha_coupons_arr = array(
        
"id" => $ha_coupons->id,
"coupon_name" => $ha_coupons->coupon_name,
"coupon_code" => $ha_coupons->coupon_code,
"discount_type" => $ha_coupons->discount_type,
"coupon_amount" => $ha_coupons->coupon_amount,
"description" => $ha_coupons->description,
"minimum_spend" => $ha_coupons->minimum_spend,
"maximum_spend" => $ha_coupons->maximum_spend,
"usage_limit_per_coupon" => $ha_coupons->usage_limit_per_coupon,
"usage_limit_per_user" => $ha_coupons->usage_limit_per_user,
"status" => $ha_coupons->status,
"start_date" => $ha_coupons->start_date,
"end_date" => $ha_coupons->end_date,
"created_at" => $ha_coupons->created_at,
"updated_at" => $ha_coupons->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_coupons found","data"=> $ha_coupons_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_coupons does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_coupons does not exist.","data"=> ""));
}
?>
