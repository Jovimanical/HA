<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_languages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_languages object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_languages = new Ha_Languages($db);

$ha_languages->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_languages->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_languages will be here

// query ha_languages
$stmt = $ha_languages->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_languages array
    $ha_languages_arr=array();
	$ha_languages_arr["pageno"]=$ha_languages->pageNo;
	$ha_languages_arr["pagesize"]=$ha_languages->no_of_records_per_page;
    $ha_languages_arr["total_count"]=$ha_languages->total_record_count();
    $ha_languages_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_languages_item=array(
            
"id" => $id,
"name" => $name,
"code" => $code,
"icon" => html_entity_decode($icon),
"text_align" => $text_align,
"is_default" => $is_default,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_languages_arr["records"], $ha_languages_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_languages data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_languages found","data"=> $ha_languages_arr));
    
}else{
 // no ha_languages found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_languages found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_languages found.","data"=> ""));
    
}
 


