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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_password_resets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_password_resets object
$ha_password_resets = new Ha_Password_Resets($db);
 
// set ID property of record to read
$ha_password_resets->ERROR_NOPRIMARYKEYFOUND = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_password_resets to be edited
$ha_password_resets->readOne();
 
if($ha_password_resets->ERROR_NOPRIMARYKEYFOUND!=null){
    // create array
    $ha_password_resets_arr = array(
        
"email" => $ha_password_resets->email,
"token" => $ha_password_resets->token,
"created_at" => $ha_password_resets->created_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_password_resets found","data"=> $ha_password_resets_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_password_resets does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_password_resets does not exist.","data"=> ""));
}
?>
