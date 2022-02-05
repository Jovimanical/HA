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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_languages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_languages object
$ha_languages = new Ha_Languages($db);
 
// set ID property of record to read
$ha_languages->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_languages to be edited
$ha_languages->readOne();
 
if($ha_languages->id!=null){
    // create array
    $ha_languages_arr = array(
        
"id" => $ha_languages->id,
"name" => $ha_languages->name,
"code" => $ha_languages->code,
"icon" => html_entity_decode($ha_languages->icon),
"text_align" => $ha_languages->text_align,
"is_default" => $ha_languages->is_default,
"created_at" => $ha_languages->created_at,
"updated_at" => $ha_languages->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_languages found","data"=> $ha_languages_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_languages does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_languages does not exist.","data"=> ""));
}
?>
