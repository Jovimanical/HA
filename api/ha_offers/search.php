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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_offers.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_offers object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_offers = new Ha_Offers($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_offers->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_offers->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_offers
$stmt = $ha_offers->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_offers array
    $ha_offers_arr=array();
	$ha_offers_arr["pageno"]=$ha_offers->pageNo;
	$ha_offers_arr["pagesize"]=$ha_offers->no_of_records_per_page;
    $ha_offers_arr["total_count"]=$ha_offers->total_record_count();
    $ha_offers_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_offers_item=array(
            
"id" => $id,
"name" => $name,
"discount_type" => $discount_type,
"amount" => $amount,
"start_date" => $start_date,
"end_date" => $end_date,
"status" => $status,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_offers_arr["records"], $ha_offers_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_offers data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_offers found","data"=> $ha_offers_arr));
    
}else{
 // no ha_offers found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_offers found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_offers found.","data"=> ""));
    
}
 


