<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_categories.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_categories object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_categories = new Ha_Categories($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_categories->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_categories->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_categories
$stmt = $ha_categories->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_categories array
    $ha_categories_arr=array();
	$ha_categories_arr["pageno"]=$ha_categories->pageNo;
	$ha_categories_arr["pagesize"]=$ha_categories->no_of_records_per_page;
    $ha_categories_arr["total_count"]=$ha_categories->total_record_count();
    $ha_categories_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_categories_item=array(
            
"id" => $id,
"parent_id" => $parent_id,
"name" => $name,
"icon" => $icon,
"meta_title" => html_entity_decode($meta_title),
"meta_description" => html_entity_decode($meta_description),
"meta_keywords" => $meta_keywords,
"image" => html_entity_decode($image),
"is_top" => $is_top,
"is_special" => $is_special,
"in_filter_menu" => $in_filter_menu,
"created_at" => $created_at,
"updated_at" => $updated_at,
"deleted_at" => $deleted_at
        );
 
        array_push($ha_categories_arr["records"], $ha_categories_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_categories data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_categories found","data"=> $ha_categories_arr));
    
}else{
 // no ha_categories found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_categories found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_categories found.","data"=> ""));
    
}
 


