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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_email_sms_templates.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_email_sms_templates object
$ha_email_sms_templates = new Ha_Email_Sms_Templates($db);
 
// set ID property of record to read
$ha_email_sms_templates->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_email_sms_templates to be edited
$ha_email_sms_templates->readOne();
 
if($ha_email_sms_templates->id!=null){
    // create array
    $ha_email_sms_templates_arr = array(
        
"id" => $ha_email_sms_templates->id,
"act" => $ha_email_sms_templates->act,
"name" => $ha_email_sms_templates->name,
"subj" => html_entity_decode($ha_email_sms_templates->subj),
"email_body" => $ha_email_sms_templates->email_body,
"sms_body" => $ha_email_sms_templates->sms_body,
"shortcodes" => $ha_email_sms_templates->shortcodes,
"email_status" => $ha_email_sms_templates->email_status,
"sms_status" => $ha_email_sms_templates->sms_status,
"created_at" => $ha_email_sms_templates->created_at,
"updated_at" => $ha_email_sms_templates->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_email_sms_templates found","data"=> $ha_email_sms_templates_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_email_sms_templates does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_email_sms_templates does not exist.","data"=> ""));
}
?>
