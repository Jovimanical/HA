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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_agents_password_resets.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_agents_password_resets object
$ha_agents_password_resets = new Ha_Agents_Password_Resets($db);
 
// get id of ha_agents_password_resets to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_agents_password_resets to be edited
$ha_agents_password_resets->id = $data->id;

if(
!isEmpty($data->email)
&&!isEmpty($data->token)
&&!isEmpty($data->status)
){
// set ha_agents_password_resets property values

if(!isEmpty($data->email)) { 
$ha_agents_password_resets->email = $data->email;
} else { 
$ha_agents_password_resets->email = '';
}
if(!isEmpty($data->token)) { 
$ha_agents_password_resets->token = $data->token;
} else { 
$ha_agents_password_resets->token = '';
}
if(!isEmpty($data->status)) { 
$ha_agents_password_resets->status = $data->status;
} else { 
$ha_agents_password_resets->status = '';
}
$ha_agents_password_resets->created_at = $data->created_at;
$ha_agents_password_resets->updated_at = $data->updated_at;
 
// update the ha_agents_password_resets
if($ha_agents_password_resets->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_agents_password_resets, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_agents_password_resets","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_agents_password_resets. Data is incomplete.","data"=> ""));
}
?>
