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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_admins.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_admins object
$ha_admins = new Ha_Admins($db);
 
// set ID property of record to read
$ha_admins->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_admins to be edited
$ha_admins->readOne();
 
if($ha_admins->id!=null){
    // create array
    $ha_admins_arr = array(
        
"id" => $ha_admins->id,
"name" => $ha_admins->name,
"email" => $ha_admins->email,
"username" => $ha_admins->username,
"email_verified_at" => $ha_admins->email_verified_at,
"image" => html_entity_decode($ha_admins->image),
"password" => html_entity_decode($ha_admins->password),
"created_at" => $ha_admins->created_at,
"updated_at" => $ha_admins->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_admins found","data"=> $ha_admins_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_admins does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_admins does not exist.","data"=> ""));
}
?>
