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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_languages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_languages object
$ha_languages = new Ha_Languages($db);
 
// get id of ha_languages to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_languages to be edited
$ha_languages->id = $data->id;

if(
!isEmpty($data->name)
&&!isEmpty($data->code)
&&!isEmpty($data->text_align)
&&!isEmpty($data->is_default)
){
// set ha_languages property values

if(!isEmpty($data->name)) { 
$ha_languages->name = $data->name;
} else { 
$ha_languages->name = '';
}
if(!isEmpty($data->code)) { 
$ha_languages->code = $data->code;
} else { 
$ha_languages->code = '';
}
$ha_languages->icon = $data->icon;
if(!isEmpty($data->text_align)) { 
$ha_languages->text_align = $data->text_align;
} else { 
$ha_languages->text_align = '0';
}
if(!isEmpty($data->is_default)) { 
$ha_languages->is_default = $data->is_default;
} else { 
$ha_languages->is_default = '0';
}
$ha_languages->created_at = $data->created_at;
$ha_languages->updated_at = $data->updated_at;
 
// update the ha_languages
if($ha_languages->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_languages, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_languages","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_languages. Data is incomplete.","data"=> ""));
}
?>
