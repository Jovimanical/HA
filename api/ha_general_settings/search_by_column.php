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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_general_settings.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_general_settings object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_general_settings = new Ha_General_Settings($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_general_settings->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_general_settings->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_general_settings
$stmt = $ha_general_settings->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_general_settings array
    $ha_general_settings_arr=array();
	$ha_general_settings_arr["pageno"]=$ha_general_settings->pageNo;
	$ha_general_settings_arr["pagesize"]=$ha_general_settings->no_of_records_per_page;
    $ha_general_settings_arr["total_count"]=$ha_general_settings->search_record_count($data,$orAnd);
    $ha_general_settings_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_general_settings_item=array(
            
"id" => $id,
"sitename" => $sitename,
"cur_text" => $cur_text,
"cur_sym" => $cur_sym,
"email_from" => $email_from,
"email_template" => $email_template,
"sms_api" => html_entity_decode($sms_api),
"base_color" => $base_color,
"secondary_color" => $secondary_color,
"mail_config" => $mail_config,
"sms_config" => $sms_config,
"ev" => $ev,
"en" => $en,
"sv" => $sv,
"sn" => $sn,
"force_ssl" => $force_ssl,
"secure_password" => $secure_password,
"agree" => $agree,
"cod" => $cod,
"registration" => $registration,
"active_template" => $active_template,
"product_commission" => $product_commission,
"seller_withdraw_limit" => $seller_withdraw_limit,
"sys_version" => $sys_version,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_general_settings_arr["records"], $ha_general_settings_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_general_settings data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_general_settings found","data"=> $ha_general_settings_arr));
    
}else{
 // no ha_general_settings found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_general_settings found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_general_settings found.","data"=> ""));
    
}
 


