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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdrawals.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_withdrawals object
$ha_withdrawals = new Ha_Withdrawals($db);
 
// set ID property of record to read
$ha_withdrawals->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_withdrawals to be edited
$ha_withdrawals->readOne();
 
if($ha_withdrawals->id!=null){
    // create array
    $ha_withdrawals_arr = array(
        
"id" => $ha_withdrawals->id,
"method_id" => $ha_withdrawals->method_id,
"seller_id" => $ha_withdrawals->seller_id,
"amount" => $ha_withdrawals->amount,
"currency" => $ha_withdrawals->currency,
"rate" => $ha_withdrawals->rate,
"charge" => $ha_withdrawals->charge,
"trx" => $ha_withdrawals->trx,
"final_amount" => $ha_withdrawals->final_amount,
"after_charge" => $ha_withdrawals->after_charge,
"withdraw_information" => $ha_withdrawals->withdraw_information,
"status" => $ha_withdrawals->status,
"admin_feedback" => $ha_withdrawals->admin_feedback,
"created_at" => $ha_withdrawals->created_at,
"updated_at" => $ha_withdrawals->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_withdrawals found","data"=> $ha_withdrawals_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_withdrawals does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_withdrawals does not exist.","data"=> ""));
}
?>
