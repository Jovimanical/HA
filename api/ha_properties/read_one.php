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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_properties.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_properties object
$ha_properties = new Ha_Properties($db);
 
// set ID property of record to read
$ha_properties->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_properties to be edited
$ha_properties->readOne();
 
if($ha_properties->id!=null){
    // create array
    $ha_properties_arr = array(
        
"id" => $ha_properties->id,
"seller_id" => $ha_properties->seller_id,
"brand_id" => $ha_properties->brand_id,
"sku" => $ha_properties->sku,
"name" => html_entity_decode($ha_properties->name),
"model" => $ha_properties->model,
"has_variants" => $ha_properties->has_variants,
"track_inventory" => $ha_properties->track_inventory,
"show_in_frontend" => $ha_properties->show_in_frontend,
"main_image" => $ha_properties->main_image,
"video_link" => $ha_properties->video_link,
"description" => $ha_properties->description,
"summary" => $ha_properties->summary,
"specification" => $ha_properties->specification,
"extra_descriptions" => $ha_properties->extra_descriptions,
"base_price" => $ha_properties->base_price,
"is_featured" => $ha_properties->is_featured,
"meta_title" => html_entity_decode($ha_properties->meta_title),
"meta_description" => html_entity_decode($ha_properties->meta_description),
"meta_keywords" => $ha_properties->meta_keywords,
"status" => $ha_properties->status,
"sold" => $ha_properties->sold,
"created_at" => $ha_properties->created_at,
"updated_at" => $ha_properties->updated_at,
"deleted_at" => $ha_properties->deleted_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_properties found","data"=> $ha_properties_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_properties does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_properties does not exist.","data"=> ""));
}
?>
