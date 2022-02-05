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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_categories.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_categories object
$ha_categories = new Ha_Categories($db);
 
// set ID property of record to read
$ha_categories->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_categories to be edited
$ha_categories->readOne();
 
if($ha_categories->id!=null){
    // create array
    $ha_categories_arr = array(
        
"id" => $ha_categories->id,
"parent_id" => $ha_categories->parent_id,
"name" => $ha_categories->name,
"icon" => $ha_categories->icon,
"meta_title" => html_entity_decode($ha_categories->meta_title),
"meta_description" => html_entity_decode($ha_categories->meta_description),
"meta_keywords" => $ha_categories->meta_keywords,
"image" => html_entity_decode($ha_categories->image),
"is_top" => $ha_categories->is_top,
"is_special" => $ha_categories->is_special,
"in_filter_menu" => $ha_categories->in_filter_menu,
"created_at" => $ha_categories->created_at,
"updated_at" => $ha_categories->updated_at,
"deleted_at" => $ha_categories->deleted_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_categories found","data"=> $ha_categories_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_categories does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_categories does not exist.","data"=> ""));
}
?>
