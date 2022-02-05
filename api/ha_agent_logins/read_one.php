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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_agent_logins.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_agent_logins object
$ha_agent_logins = new Ha_Agent_Logins($db);
 
// set ID property of record to read
$ha_agent_logins->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_agent_logins to be edited
$ha_agent_logins->readOne();
 
if($ha_agent_logins->id!=null){
    // create array
    $ha_agent_logins_arr = array(
        
"id" => $ha_agent_logins->id,
"seller_id" => $ha_agent_logins->seller_id,
"seller_ip" => $ha_agent_logins->seller_ip,
"city" => $ha_agent_logins->city,
"country" => $ha_agent_logins->country,
"country_code" => $ha_agent_logins->country_code,
"longitude" => $ha_agent_logins->longitude,
"latitude" => $ha_agent_logins->latitude,
"browser" => $ha_agent_logins->browser,
"os" => $ha_agent_logins->os,
"created_at" => $ha_agent_logins->created_at,
"updated_at" => $ha_agent_logins->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_agent_logins found","data"=> $ha_agent_logins_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_agent_logins does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_agent_logins does not exist.","data"=> ""));
}
?>
