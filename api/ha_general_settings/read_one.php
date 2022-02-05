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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_general_settings.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_general_settings object
$ha_general_settings = new Ha_General_Settings($db);
 
// set ID property of record to read
$ha_general_settings->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_general_settings to be edited
$ha_general_settings->readOne();
 
if($ha_general_settings->id!=null){
    // create array
    $ha_general_settings_arr = array(
        
"id" => $ha_general_settings->id,
"sitename" => $ha_general_settings->sitename,
"cur_text" => $ha_general_settings->cur_text,
"cur_sym" => $ha_general_settings->cur_sym,
"email_from" => $ha_general_settings->email_from,
"email_template" => $ha_general_settings->email_template,
"sms_api" => html_entity_decode($ha_general_settings->sms_api),
"base_color" => $ha_general_settings->base_color,
"secondary_color" => $ha_general_settings->secondary_color,
"mail_config" => $ha_general_settings->mail_config,
"sms_config" => $ha_general_settings->sms_config,
"ev" => $ha_general_settings->ev,
"en" => $ha_general_settings->en,
"sv" => $ha_general_settings->sv,
"sn" => $ha_general_settings->sn,
"force_ssl" => $ha_general_settings->force_ssl,
"secure_password" => $ha_general_settings->secure_password,
"agree" => $ha_general_settings->agree,
"cod" => $ha_general_settings->cod,
"registration" => $ha_general_settings->registration,
"active_template" => $ha_general_settings->active_template,
"product_commission" => $ha_general_settings->product_commission,
"seller_withdraw_limit" => $ha_general_settings->seller_withdraw_limit,
"sys_version" => $ha_general_settings->sys_version,
"created_at" => $ha_general_settings->created_at,
"updated_at" => $ha_general_settings->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_general_settings found","data"=> $ha_general_settings_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_general_settings does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_general_settings does not exist.","data"=> ""));
}
?>
