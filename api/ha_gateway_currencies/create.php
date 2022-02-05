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
 
// instantiate ha_gateway_currencies object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_gateway_currencies.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_gateway_currencies = new Ha_Gateway_Currencies($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->min_amount)
&&!isEmpty($data->max_amount)
&&!isEmpty($data->percent_charge)
&&!isEmpty($data->fixed_charge)
&&!isEmpty($data->rate)){
 
    // set ha_gateway_currencies property values
	 
$ha_gateway_currencies->name = $data->name;
$ha_gateway_currencies->currency = $data->currency;
$ha_gateway_currencies->symbol = $data->symbol;
$ha_gateway_currencies->method_code = $data->method_code;
$ha_gateway_currencies->gateway_alias = $data->gateway_alias;
if(!isEmpty($data->min_amount)) { 
$ha_gateway_currencies->min_amount = $data->min_amount;
} else { 
$ha_gateway_currencies->min_amount = '0.00000000';
}
if(!isEmpty($data->max_amount)) { 
$ha_gateway_currencies->max_amount = $data->max_amount;
} else { 
$ha_gateway_currencies->max_amount = '0.00000000';
}
if(!isEmpty($data->percent_charge)) { 
$ha_gateway_currencies->percent_charge = $data->percent_charge;
} else { 
$ha_gateway_currencies->percent_charge = '0.00';
}
if(!isEmpty($data->fixed_charge)) { 
$ha_gateway_currencies->fixed_charge = $data->fixed_charge;
} else { 
$ha_gateway_currencies->fixed_charge = '0.00000000';
}
if(!isEmpty($data->rate)) { 
$ha_gateway_currencies->rate = $data->rate;
} else { 
$ha_gateway_currencies->rate = '0.00000000';
}
$ha_gateway_currencies->image = $data->image;
$ha_gateway_currencies->gateway_parameter = $data->gateway_parameter;
$ha_gateway_currencies->created_at = $data->created_at;
$ha_gateway_currencies->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_gateway_currencies->create();
    // create the ha_gateway_currencies
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_gateway_currencies, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_gateway_currencies","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_gateway_currencies. Data is incomplete.","data"=> ""));
}
?>
