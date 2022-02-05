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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wallet.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_wallet object
$ha_wallet = new Ha_Wallet($db);
 
// set ID property of record to read
$ha_wallet->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_wallet to be edited
$ha_wallet->readOne();
 
if($ha_wallet->id!=null){
    // create array
    $ha_wallet_arr = array(
        
"username" => $ha_wallet->username,
"id" => $ha_wallet->id,
"users_id" => $ha_wallet->users_id,
"flag" => $ha_wallet->flag,
"amount" => $ha_wallet->amount,
"description" => html_entity_decode($ha_wallet->description),
"created_at" => $ha_wallet->created_at,
"updated_at" => $ha_wallet->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_wallet found","data"=> $ha_wallet_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_wallet does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_wallet does not exist.","data"=> ""));
}
?>
