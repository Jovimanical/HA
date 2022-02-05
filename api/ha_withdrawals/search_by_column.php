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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdrawals.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_withdrawals object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_withdrawals = new Ha_Withdrawals($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_withdrawals->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_withdrawals->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_withdrawals
$stmt = $ha_withdrawals->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_withdrawals array
    $ha_withdrawals_arr=array();
	$ha_withdrawals_arr["pageno"]=$ha_withdrawals->pageNo;
	$ha_withdrawals_arr["pagesize"]=$ha_withdrawals->no_of_records_per_page;
    $ha_withdrawals_arr["total_count"]=$ha_withdrawals->search_record_count($data,$orAnd);
    $ha_withdrawals_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_withdrawals_item=array(
            
"id" => $id,
"method_id" => $method_id,
"seller_id" => $seller_id,
"amount" => $amount,
"currency" => $currency,
"rate" => $rate,
"charge" => $charge,
"trx" => $trx,
"final_amount" => $final_amount,
"after_charge" => $after_charge,
"withdraw_information" => $withdraw_information,
"status" => $status,
"admin_feedback" => $admin_feedback,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_withdrawals_arr["records"], $ha_withdrawals_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_withdrawals data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_withdrawals found","data"=> $ha_withdrawals_arr));
    
}else{
 // no ha_withdrawals found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_withdrawals found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_withdrawals found.","data"=> ""));
    
}
 


