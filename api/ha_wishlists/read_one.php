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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wishlists.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_wishlists object
$ha_wishlists = new Ha_Wishlists($db);
 
// set ID property of record to read
$ha_wishlists->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_wishlists to be edited
$ha_wishlists->readOne();
 
if($ha_wishlists->id!=null){
    // create array
    $ha_wishlists_arr = array(
        
"id" => $ha_wishlists->id,
"user_id" => $ha_wishlists->user_id,
"session_id" => html_entity_decode($ha_wishlists->session_id),
"product_id" => $ha_wishlists->product_id,
"created_at" => $ha_wishlists->created_at,
"updated_at" => $ha_wishlists->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_wishlists found","data"=> $ha_wishlists_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_wishlists does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_wishlists does not exist.","data"=> ""));
}
?>
