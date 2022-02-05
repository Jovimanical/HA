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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_offers.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_offers object
$ha_offers = new Ha_Offers($db);
 
// set ID property of record to read
$ha_offers->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_offers to be edited
$ha_offers->readOne();
 
if($ha_offers->id!=null){
    // create array
    $ha_offers_arr = array(
        
"id" => $ha_offers->id,
"name" => $ha_offers->name,
"discount_type" => $ha_offers->discount_type,
"amount" => $ha_offers->amount,
"start_date" => $ha_offers->start_date,
"end_date" => $ha_offers->end_date,
"status" => $ha_offers->status,
"created_at" => $ha_offers->created_at,
"updated_at" => $ha_offers->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_offers found","data"=> $ha_offers_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_offers does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_offers does not exist.","data"=> ""));
}
?>
