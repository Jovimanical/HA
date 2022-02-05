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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_brands.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_brands object
$ha_brands = new Ha_Brands($db);
 
// set ID property of record to read
$ha_brands->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_brands to be edited
$ha_brands->readOne();
 
if($ha_brands->id!=null){
    // create array
    $ha_brands_arr = array(
        
"id" => $ha_brands->id,
"name" => $ha_brands->name,
"logo" => html_entity_decode($ha_brands->logo),
"top" => $ha_brands->top,
"meta_title" => html_entity_decode($ha_brands->meta_title),
"meta_description" => html_entity_decode($ha_brands->meta_description),
"meta_keywords" => $ha_brands->meta_keywords,
"created_at" => $ha_brands->created_at,
"updated_at" => $ha_brands->updated_at,
"deleted_at" => $ha_brands->deleted_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_brands found","data"=> $ha_brands_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_brands does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_brands does not exist.","data"=> ""));
}
?>
