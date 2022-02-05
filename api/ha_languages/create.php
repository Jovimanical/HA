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
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
 
// instantiate ha_languages object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_languages.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_languages = new Ha_Languages($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->name)
&&!isEmpty($data->code)
&&!isEmpty($data->text_align)
&&!isEmpty($data->is_default)){
 
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
 	$lastInsertedId=$ha_languages->create();
    // create the ha_languages
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_languages, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_languages","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_languages. Data is incomplete.","data"=> ""));
}
?>
