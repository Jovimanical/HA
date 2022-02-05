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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_product_reviews.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_product_reviews object
$ha_product_reviews = new Ha_Product_Reviews($db);
 
// set ID property of record to read
$ha_product_reviews->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_product_reviews to be edited
$ha_product_reviews->readOne();
 
if($ha_product_reviews->id!=null){
    // create array
    $ha_product_reviews_arr = array(
        
"id" => $ha_product_reviews->id,
"user_id" => $ha_product_reviews->user_id,
"product_id" => $ha_product_reviews->product_id,
"review" => $ha_product_reviews->review,
"rating" => $ha_product_reviews->rating,
"created_at" => $ha_product_reviews->created_at,
"updated_at" => $ha_product_reviews->updated_at,
"deleted_at" => $ha_product_reviews->deleted_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_product_reviews found","data"=> $ha_product_reviews_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_product_reviews does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_product_reviews does not exist.","data"=> ""));
}
?>
