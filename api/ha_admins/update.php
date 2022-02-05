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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_admins.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_admins object
$ha_admins = new Ha_Admins($db);
 
// get id of ha_admins to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_admins to be edited
$ha_admins->id = $data->id;

if(
!isEmpty($data->name)
&&!isEmpty($data->email)
&&!isEmpty($data->username)
&&!isEmpty($data->password)
){
// set ha_admins property values

if(!isEmpty($data->name)) { 
$ha_admins->name = $data->name;
} else { 
$ha_admins->name = '';
}
if(!isEmpty($data->email)) { 
$ha_admins->email = $data->email;
} else { 
$ha_admins->email = '';
}
if(!isEmpty($data->username)) { 
$ha_admins->username = $data->username;
} else { 
$ha_admins->username = '';
}
$ha_admins->email_verified_at = $data->email_verified_at;
$ha_admins->image = $data->image;
if(!isEmpty($data->password)) { 
$ha_admins->password = $data->password;
} else { 
$ha_admins->password = '';
}
$ha_admins->created_at = $data->created_at;
$ha_admins->updated_at = $data->updated_at;
 
// update the ha_admins
if($ha_admins->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_admins, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_admins","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_admins. Data is incomplete.","data"=> ""));
}
?>
