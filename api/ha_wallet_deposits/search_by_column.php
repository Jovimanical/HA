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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wallet_deposits.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_wallet_deposits object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_wallet_deposits = new Ha_Wallet_Deposits($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_wallet_deposits->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_wallet_deposits->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_wallet_deposits
$stmt = $ha_wallet_deposits->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_wallet_deposits array
    $ha_wallet_deposits_arr=array();
	$ha_wallet_deposits_arr["pageno"]=$ha_wallet_deposits->pageNo;
	$ha_wallet_deposits_arr["pagesize"]=$ha_wallet_deposits->no_of_records_per_page;
    $ha_wallet_deposits_arr["total_count"]=$ha_wallet_deposits->search_record_count($data,$orAnd);
    $ha_wallet_deposits_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_wallet_deposits_item=array(
            
"id" => $id,
"user_id" => $user_id,
"order_id" => $order_id,
"method_code" => $method_code,
"amount" => $amount,
"method_currency" => $method_currency,
"charge" => $charge,
"rate" => $rate,
"final_amo" => $final_amo,
"detail" => $detail,
"btc_amo" => html_entity_decode($btc_amo),
"btc_wallet" => html_entity_decode($btc_wallet),
"trx" => $trx,
"try" => $try,
"status" => $status,
"from_api" => $from_api,
"admin_feedback" => html_entity_decode($admin_feedback),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_wallet_deposits_arr["records"], $ha_wallet_deposits_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_wallet_deposits data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_wallet_deposits found","data"=> $ha_wallet_deposits_arr));
    
}else{
 // no ha_wallet_deposits found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_wallet_deposits found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_wallet_deposits found.","data"=> ""));
    
}
 


