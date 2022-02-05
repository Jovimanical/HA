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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_brands.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_brands object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_brands = new Ha_Brands($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_brands->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_brands->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_brands
$stmt = $ha_brands->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_brands array
    $ha_brands_arr=array();
	$ha_brands_arr["pageno"]=$ha_brands->pageNo;
	$ha_brands_arr["pagesize"]=$ha_brands->no_of_records_per_page;
    $ha_brands_arr["total_count"]=$ha_brands->search_record_count($data,$orAnd);
    $ha_brands_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_brands_item=array(
            
"id" => $id,
"name" => $name,
"logo" => html_entity_decode($logo),
"top" => $top,
"meta_title" => html_entity_decode($meta_title),
"meta_description" => html_entity_decode($meta_description),
"meta_keywords" => $meta_keywords,
"created_at" => $created_at,
"updated_at" => $updated_at,
"deleted_at" => $deleted_at
        );
 
        array_push($ha_brands_arr["records"], $ha_brands_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_brands data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_brands found","data"=> $ha_brands_arr));
    
}else{
 // no ha_brands found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_brands found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_brands found.","data"=> ""));
    
}
 


