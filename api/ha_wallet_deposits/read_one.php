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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wallet_deposits.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_wallet_deposits object
$ha_wallet_deposits = new Ha_Wallet_Deposits($db);
 
// set ID property of record to read
$ha_wallet_deposits->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_wallet_deposits to be edited
$ha_wallet_deposits->readOne();
 
if($ha_wallet_deposits->id!=null){
    // create array
    $ha_wallet_deposits_arr = array(
        
"id" => $ha_wallet_deposits->id,
"user_id" => $ha_wallet_deposits->user_id,
"order_id" => $ha_wallet_deposits->order_id,
"method_code" => $ha_wallet_deposits->method_code,
"amount" => $ha_wallet_deposits->amount,
"method_currency" => $ha_wallet_deposits->method_currency,
"charge" => $ha_wallet_deposits->charge,
"rate" => $ha_wallet_deposits->rate,
"final_amo" => $ha_wallet_deposits->final_amo,
"detail" => $ha_wallet_deposits->detail,
"btc_amo" => html_entity_decode($ha_wallet_deposits->btc_amo),
"btc_wallet" => html_entity_decode($ha_wallet_deposits->btc_wallet),
"trx" => $ha_wallet_deposits->trx,
"try" => $ha_wallet_deposits->try,
"status" => $ha_wallet_deposits->status,
"from_api" => $ha_wallet_deposits->from_api,
"admin_feedback" => html_entity_decode($ha_wallet_deposits->admin_feedback),
"created_at" => $ha_wallet_deposits->created_at,
"updated_at" => $ha_wallet_deposits->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_wallet_deposits found","data"=> $ha_wallet_deposits_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_wallet_deposits does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_wallet_deposits does not exist.","data"=> ""));
}
?>
