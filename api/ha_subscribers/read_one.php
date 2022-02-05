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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_subscribers.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_subscribers object
$ha_subscribers = new Ha_Subscribers($db);
 
// set ID property of record to read
$ha_subscribers->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_subscribers to be edited
$ha_subscribers->readOne();
 
if($ha_subscribers->id!=null){
    // create array
    $ha_subscribers_arr = array(
        
"id" => $ha_subscribers->id,
"email" => $ha_subscribers->email,
"created_at" => $ha_subscribers->created_at,
"updated_at" => $ha_subscribers->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_subscribers found","data"=> $ha_subscribers_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_subscribers does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_subscribers does not exist.","data"=> ""));
}
?>
