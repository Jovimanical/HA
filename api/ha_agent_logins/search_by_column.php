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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_agent_logins.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_agent_logins object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_agent_logins = new Ha_Agent_Logins($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_agent_logins->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_agent_logins->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_agent_logins
$stmt = $ha_agent_logins->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_agent_logins array
    $ha_agent_logins_arr=array();
	$ha_agent_logins_arr["pageno"]=$ha_agent_logins->pageNo;
	$ha_agent_logins_arr["pagesize"]=$ha_agent_logins->no_of_records_per_page;
    $ha_agent_logins_arr["total_count"]=$ha_agent_logins->search_record_count($data,$orAnd);
    $ha_agent_logins_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_agent_logins_item=array(
            
"id" => $id,
"seller_id" => $seller_id,
"seller_ip" => $seller_ip,
"city" => $city,
"country" => $country,
"country_code" => $country_code,
"longitude" => $longitude,
"latitude" => $latitude,
"browser" => $browser,
"os" => $os,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_agent_logins_arr["records"], $ha_agent_logins_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_agent_logins data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_agent_logins found","data"=> $ha_agent_logins_arr));
    
}else{
 // no ha_agent_logins found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_agent_logins found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_agent_logins found.","data"=> ""));
    
}
 


