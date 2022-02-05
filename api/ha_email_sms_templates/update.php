<?php
// required headers
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_email_sms_templates.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_email_sms_templates object
$ha_email_sms_templates = new Ha_Email_Sms_Templates($db);
 
// get id of ha_email_sms_templates to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_email_sms_templates to be edited
$ha_email_sms_templates->id = $data->id;

if(
!isEmpty($data->act)
&&!isEmpty($data->name)
&&!isEmpty($data->subj)
&&!isEmpty($data->shortcodes)
&&!isEmpty($data->email_status)
&&!isEmpty($data->sms_status)
){
// set ha_email_sms_templates property values

if(!isEmpty($data->act)) { 
$ha_email_sms_templates->act = $data->act;
} else { 
$ha_email_sms_templates->act = '';
}
if(!isEmpty($data->name)) { 
$ha_email_sms_templates->name = $data->name;
} else { 
$ha_email_sms_templates->name = '';
}
if(!isEmpty($data->subj)) { 
$ha_email_sms_templates->subj = $data->subj;
} else { 
$ha_email_sms_templates->subj = '';
}
$ha_email_sms_templates->email_body = $data->email_body;
$ha_email_sms_templates->sms_body = $data->sms_body;
if(!isEmpty($data->shortcodes)) { 
$ha_email_sms_templates->shortcodes = $data->shortcodes;
} else { 
$ha_email_sms_templates->shortcodes = '';
}
if(!isEmpty($data->email_status)) { 
$ha_email_sms_templates->email_status = $data->email_status;
} else { 
$ha_email_sms_templates->email_status = '1';
}
if(!isEmpty($data->sms_status)) { 
$ha_email_sms_templates->sms_status = $data->sms_status;
} else { 
$ha_email_sms_templates->sms_status = '1';
}
$ha_email_sms_templates->created_at = $data->created_at;
$ha_email_sms_templates->updated_at = $data->updated_at;
 
// update the ha_email_sms_templates
if($ha_email_sms_templates->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_email_sms_templates, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_email_sms_templates","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_email_sms_templates. Data is incomplete.","data"=> ""));
}
?>
