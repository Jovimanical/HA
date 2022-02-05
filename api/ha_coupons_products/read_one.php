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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_coupons_products.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_coupons_products object
$ha_coupons_products = new Ha_Coupons_Products($db);
 
// set ID property of record to read
$ha_coupons_products->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_coupons_products to be edited
$ha_coupons_products->readOne();
 
if($ha_coupons_products->id!=null){
    // create array
    $ha_coupons_products_arr = array(
        
"id" => $ha_coupons_products->id,
"coupon_name" => $ha_coupons_products->coupon_name,
"coupon_id" => $ha_coupons_products->coupon_id,
"name" => html_entity_decode($ha_coupons_products->name),
"product_id" => $ha_coupons_products->product_id
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_coupons_products found","data"=> $ha_coupons_products_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_coupons_products does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_coupons_products does not exist.","data"=> ""));
}
?>
