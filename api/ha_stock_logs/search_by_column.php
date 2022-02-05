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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_stock_logs.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_stock_logs object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_stock_logs = new Ha_Stock_Logs($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_stock_logs->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_stock_logs->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_stock_logs
$stmt = $ha_stock_logs->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_stock_logs array
    $ha_stock_logs_arr=array();
	$ha_stock_logs_arr["pageno"]=$ha_stock_logs->pageNo;
	$ha_stock_logs_arr["pagesize"]=$ha_stock_logs->no_of_records_per_page;
    $ha_stock_logs_arr["total_count"]=$ha_stock_logs->search_record_count($data,$orAnd);
    $ha_stock_logs_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_stock_logs_item=array(
            
"id" => $id,
"stock_id" => $stock_id,
"quantity" => $quantity,
"type" => $type,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_stock_logs_arr["records"], $ha_stock_logs_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_stock_logs data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_stock_logs found","data"=> $ha_stock_logs_arr));
    
}else{
 // no ha_stock_logs found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_stock_logs found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_stock_logs found.","data"=> ""));
    
}
 


