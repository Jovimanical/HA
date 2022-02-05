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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_transactions.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_transactions object
$ha_transactions = new Ha_Transactions($db);
 
// set ID property of record to read
$ha_transactions->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_transactions to be edited
$ha_transactions->readOne();
 
if($ha_transactions->id!=null){
    // create array
    $ha_transactions_arr = array(
        
"id" => $ha_transactions->id,
"user_id" => $ha_transactions->user_id,
"seller_id" => $ha_transactions->seller_id,
"amount" => $ha_transactions->amount,
"charge" => $ha_transactions->charge,
"post_balance" => $ha_transactions->post_balance,
"trx_type" => $ha_transactions->trx_type,
"trx" => $ha_transactions->trx,
"details" => html_entity_decode($ha_transactions->details),
"created_at" => $ha_transactions->created_at,
"updated_at" => $ha_transactions->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_transactions found","data"=> $ha_transactions_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_transactions does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_transactions does not exist.","data"=> ""));
}
?>
