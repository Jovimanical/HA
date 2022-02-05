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
 
// instantiate ha_withdraw_methods object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdraw_methods.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_withdraw_methods = new Ha_Withdraw_Methods($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->max_limit)
&&!isEmpty($data->status)){
 
    // set ha_withdraw_methods property values
	 
$ha_withdraw_methods->name = $data->name;
$ha_withdraw_methods->image = $data->image;
$ha_withdraw_methods->min_limit = $data->min_limit;
if(!isEmpty($data->max_limit)) { 
$ha_withdraw_methods->max_limit = $data->max_limit;
} else { 
$ha_withdraw_methods->max_limit = '0.00000000';
}
$ha_withdraw_methods->delay = $data->delay;
$ha_withdraw_methods->fixed_charge = $data->fixed_charge;
$ha_withdraw_methods->rate = $data->rate;
$ha_withdraw_methods->percent_charge = $data->percent_charge;
$ha_withdraw_methods->currency = $data->currency;
$ha_withdraw_methods->user_data = $data->user_data;
$ha_withdraw_methods->description = $data->description;
if(!isEmpty($data->status)) { 
$ha_withdraw_methods->status = $data->status;
} else { 
$ha_withdraw_methods->status = '1';
}
$ha_withdraw_methods->created_at = $data->created_at;
$ha_withdraw_methods->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_withdraw_methods->create();
    // create the ha_withdraw_methods
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_withdraw_methods, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_withdraw_methods","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_withdraw_methods. Data is incomplete.","data"=> ""));
}
?>
