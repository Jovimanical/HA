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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_password_resets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_password_resets object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_password_resets = new Ha_Password_Resets($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_password_resets->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_password_resets->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_password_resets
$stmt = $ha_password_resets->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_password_resets array
    $ha_password_resets_arr=array();
	$ha_password_resets_arr["pageno"]=$ha_password_resets->pageNo;
	$ha_password_resets_arr["pagesize"]=$ha_password_resets->no_of_records_per_page;
    $ha_password_resets_arr["total_count"]=$ha_password_resets->search_record_count($data,$orAnd);
    $ha_password_resets_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_password_resets_item=array(
            
"email" => $email,
"token" => $token,
"created_at" => $created_at
        );
 
        array_push($ha_password_resets_arr["records"], $ha_password_resets_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_password_resets data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_password_resets found","data"=> $ha_password_resets_arr));
    
}else{
 // no ha_password_resets found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_password_resets found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_password_resets found.","data"=> ""));
    
}
 


