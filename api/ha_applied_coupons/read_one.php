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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_applied_coupons.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_applied_coupons object
$ha_applied_coupons = new Ha_Applied_Coupons($db);
 
// set ID property of record to read
$ha_applied_coupons->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_applied_coupons to be edited
$ha_applied_coupons->readOne();
 
if($ha_applied_coupons->id!=null){
    // create array
    $ha_applied_coupons_arr = array(
        
"id" => $ha_applied_coupons->id,
"user_id" => $ha_applied_coupons->user_id,
"coupon_id" => $ha_applied_coupons->coupon_id,
"order_id" => $ha_applied_coupons->order_id,
"amount" => $ha_applied_coupons->amount,
"created_at" => $ha_applied_coupons->created_at,
"updated_at" => $ha_applied_coupons->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_applied_coupons found","data"=> $ha_applied_coupons_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_applied_coupons does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_applied_coupons does not exist.","data"=> ""));
}
?>
