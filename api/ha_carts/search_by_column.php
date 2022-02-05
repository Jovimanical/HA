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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_carts.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_carts object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_carts = new Ha_Carts($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_carts->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_carts->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_carts
$stmt = $ha_carts->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_carts array
    $ha_carts_arr=array();
	$ha_carts_arr["pageno"]=$ha_carts->pageNo;
	$ha_carts_arr["pagesize"]=$ha_carts->no_of_records_per_page;
    $ha_carts_arr["total_count"]=$ha_carts->search_record_count($data,$orAnd);
    $ha_carts_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_carts_item=array(
            
"id" => $id,
"user_id" => $user_id,
"session_id" => html_entity_decode($session_id),
"product_id" => $product_id,
"attributes" => $attributes,
"quantity" => $quantity,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_carts_arr["records"], $ha_carts_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_carts data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_carts found","data"=> $ha_carts_arr));
    
}else{
 // no ha_carts found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_carts found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_carts found.","data"=> ""));
    
}
 


