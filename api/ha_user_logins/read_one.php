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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_user_logins.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_user_logins object
$ha_user_logins = new Ha_User_Logins($db);
 
// set ID property of record to read
$ha_user_logins->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_user_logins to be edited
$ha_user_logins->readOne();
 
if($ha_user_logins->id!=null){
    // create array
    $ha_user_logins_arr = array(
        
"id" => $ha_user_logins->id,
"user_id" => $ha_user_logins->user_id,
"user_ip" => $ha_user_logins->user_ip,
"city" => $ha_user_logins->city,
"country" => $ha_user_logins->country,
"country_code" => $ha_user_logins->country_code,
"longitude" => $ha_user_logins->longitude,
"latitude" => $ha_user_logins->latitude,
"browser" => $ha_user_logins->browser,
"os" => $ha_user_logins->os,
"created_at" => $ha_user_logins->created_at,
"updated_at" => $ha_user_logins->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_user_logins found","data"=> $ha_user_logins_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_user_logins does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_user_logins does not exist.","data"=> ""));
}
?>
