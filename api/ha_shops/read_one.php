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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_shops.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_shops object
$ha_shops = new Ha_Shops($db);
 
// set ID property of record to read
$ha_shops->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_shops to be edited
$ha_shops->readOne();
 
if($ha_shops->id!=null){
    // create array
    $ha_shops_arr = array(
        
"id" => $ha_shops->id,
"seller_id" => $ha_shops->seller_id,
"name" => html_entity_decode($ha_shops->name),
"phone" => $ha_shops->phone,
"logo" => html_entity_decode($ha_shops->logo),
"cover" => html_entity_decode($ha_shops->cover),
"opens_at" => $ha_shops->opens_at,
"closed_at" => $ha_shops->closed_at,
"address" => html_entity_decode($ha_shops->address),
"social_links" => $ha_shops->social_links,
"meta_title" => html_entity_decode($ha_shops->meta_title),
"meta_description" => html_entity_decode($ha_shops->meta_description),
"meta_keywords" => $ha_shops->meta_keywords,
"created_at" => $ha_shops->created_at,
"updated_at" => $ha_shops->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_shops found","data"=> $ha_shops_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_shops does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_shops does not exist.","data"=> ""));
}
?>
