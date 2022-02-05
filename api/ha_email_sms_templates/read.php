<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_email_sms_templates.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_email_sms_templates object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_email_sms_templates = new Ha_Email_Sms_Templates($db);

$ha_email_sms_templates->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_email_sms_templates->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_email_sms_templates will be here

// query ha_email_sms_templates
$stmt = $ha_email_sms_templates->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_email_sms_templates array
    $ha_email_sms_templates_arr=array();
	$ha_email_sms_templates_arr["pageno"]=$ha_email_sms_templates->pageNo;
	$ha_email_sms_templates_arr["pagesize"]=$ha_email_sms_templates->no_of_records_per_page;
    $ha_email_sms_templates_arr["total_count"]=$ha_email_sms_templates->total_record_count();
    $ha_email_sms_templates_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_email_sms_templates_item=array(
            
"id" => $id,
"act" => $act,
"name" => $name,
"subj" => html_entity_decode($subj),
"email_body" => $email_body,
"sms_body" => $sms_body,
"shortcodes" => $shortcodes,
"email_status" => $email_status,
"sms_status" => $sms_status,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_email_sms_templates_arr["records"], $ha_email_sms_templates_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_email_sms_templates data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_email_sms_templates found","data"=> $ha_email_sms_templates_arr));
    
}else{
 // no ha_email_sms_templates found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_email_sms_templates found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_email_sms_templates found.","data"=> ""));
    
}
 


