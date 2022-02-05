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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_shops.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_shops object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_shops = new Ha_Shops($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_shops->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_shops->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_shops
$stmt = $ha_shops->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_shops array
    $ha_shops_arr=array();
	$ha_shops_arr["pageno"]=$ha_shops->pageNo;
	$ha_shops_arr["pagesize"]=$ha_shops->no_of_records_per_page;
    $ha_shops_arr["total_count"]=$ha_shops->search_record_count($data,$orAnd);
    $ha_shops_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_shops_item=array(
            
"id" => $id,
"seller_id" => $seller_id,
"name" => html_entity_decode($name),
"phone" => $phone,
"logo" => html_entity_decode($logo),
"cover" => html_entity_decode($cover),
"opens_at" => $opens_at,
"closed_at" => $closed_at,
"address" => html_entity_decode($address),
"social_links" => $social_links,
"meta_title" => html_entity_decode($meta_title),
"meta_description" => html_entity_decode($meta_description),
"meta_keywords" => $meta_keywords,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_shops_arr["records"], $ha_shops_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_shops data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_shops found","data"=> $ha_shops_arr));
    
}else{
 // no ha_shops found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_shops found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_shops found.","data"=> ""));
    
}
 


