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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_agents.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_agents object
$ha_agents = new Ha_Agents($db);
 
// set ID property of record to read
$ha_agents->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_agents to be edited
$ha_agents->readOne();
 
if($ha_agents->id!=null){
    // create array
    $ha_agents_arr = array(
        
"id" => $ha_agents->id,
"firstname" => $ha_agents->firstname,
"lastname" => $ha_agents->lastname,
"username" => $ha_agents->username,
"email" => $ha_agents->email,
"country_code" => $ha_agents->country_code,
"mobile" => $ha_agents->mobile,
"balance" => $ha_agents->balance,
"password" => html_entity_decode($ha_agents->password),
"image" => html_entity_decode($ha_agents->image),
"address" => $ha_agents->address,
"status" => $ha_agents->status,
"ev" => $ha_agents->ev,
"sv" => $ha_agents->sv,
"ver_code" => $ha_agents->ver_code,
"ver_code_send_at" => $ha_agents->ver_code_send_at,
"ts" => $ha_agents->ts,
"tv" => $ha_agents->tv,
"roles" => html_entity_decode($ha_agents->roles),
"featured" => $ha_agents->featured,
"remember_token" => html_entity_decode($ha_agents->remember_token),
"created_at" => $ha_agents->created_at,
"updated_at" => $ha_agents->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_agents found","data"=> $ha_agents_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_agents does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_agents does not exist.","data"=> ""));
}
?>
