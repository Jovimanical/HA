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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_offers_products.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_offers_products object
$ha_offers_products = new Ha_Offers_Products($db);
 
// set ID property of record to read
$ha_offers_products->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_offers_products to be edited
$ha_offers_products->readOne();
 
if($ha_offers_products->id!=null){
    // create array
    $ha_offers_products_arr = array(
        
"id" => $ha_offers_products->id,
"name" => $ha_offers_products->name,
"offer_id" => $ha_offers_products->offer_id,
"name" => html_entity_decode($ha_offers_products->name),
"product_id" => $ha_offers_products->product_id
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_offers_products found","data"=> $ha_offers_products_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_offers_products does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_offers_products does not exist.","data"=> ""));
}
?>
