<?php
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_offers_products.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_offers_products object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_offers_products = new Ha_Offers_Products($db);

$ha_offers_products->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_offers_products->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$ha_offers_products->offer_id = isset($_GET['offer_id']) ? $_GET['offer_id'] : die();
// read ha_offers_products will be here

// query ha_offers_products
$stmt = $ha_offers_products->readByoffer_id();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_offers_products array
    $ha_offers_products_arr=array();
	$ha_offers_products_arr["pageno"]=$ha_offers_products->pageNo;
	$ha_offers_products_arr["pagesize"]=$ha_offers_products->no_of_records_per_page;
    $ha_offers_products_arr["total_count"]=$ha_offers_products->total_record_count();
    $ha_offers_products_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_offers_products_item=array(
            
"id" => $id,
"name" => $name,
"offer_id" => $offer_id,
"name" => html_entity_decode($name),
"product_id" => $product_id
        );
 
        array_push($ha_offers_products_arr["records"], $ha_offers_products_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_offers_products data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_offers_products found","data"=> $ha_offers_products_arr));
    
}else{
 // no ha_offers_products found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_offers_products found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_offers_products found.","data"=> ""));
    
}
 


