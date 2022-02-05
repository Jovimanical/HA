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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdrawals.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_withdrawals object
$ha_withdrawals = new Ha_Withdrawals($db);
 
// get id of ha_withdrawals to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_withdrawals to be edited
$ha_withdrawals->id = $data->id;

if(
!isEmpty($data->method_id)
&&!isEmpty($data->seller_id)
&&!isEmpty($data->amount)
&&!isEmpty($data->currency)
&&!isEmpty($data->rate)
&&!isEmpty($data->charge)
&&!isEmpty($data->trx)
&&!isEmpty($data->final_amount)
&&!isEmpty($data->after_charge)
&&!isEmpty($data->status)
){
// set ha_withdrawals property values

if(!isEmpty($data->method_id)) { 
$ha_withdrawals->method_id = $data->method_id;
} else { 
$ha_withdrawals->method_id = '';
}
if(!isEmpty($data->seller_id)) { 
$ha_withdrawals->seller_id = $data->seller_id;
} else { 
$ha_withdrawals->seller_id = '';
}
if(!isEmpty($data->amount)) { 
$ha_withdrawals->amount = $data->amount;
} else { 
$ha_withdrawals->amount = '0.00000000';
}
if(!isEmpty($data->currency)) { 
$ha_withdrawals->currency = $data->currency;
} else { 
$ha_withdrawals->currency = '';
}
if(!isEmpty($data->rate)) { 
$ha_withdrawals->rate = $data->rate;
} else { 
$ha_withdrawals->rate = '0.00000000';
}
if(!isEmpty($data->charge)) { 
$ha_withdrawals->charge = $data->charge;
} else { 
$ha_withdrawals->charge = '0.00000000';
}
if(!isEmpty($data->trx)) { 
$ha_withdrawals->trx = $data->trx;
} else { 
$ha_withdrawals->trx = '';
}
if(!isEmpty($data->final_amount)) { 
$ha_withdrawals->final_amount = $data->final_amount;
} else { 
$ha_withdrawals->final_amount = '0.00000000';
}
if(!isEmpty($data->after_charge)) { 
$ha_withdrawals->after_charge = $data->after_charge;
} else { 
$ha_withdrawals->after_charge = '0.00000000';
}
$ha_withdrawals->withdraw_information = $data->withdraw_information;
if(!isEmpty($data->status)) { 
$ha_withdrawals->status = $data->status;
} else { 
$ha_withdrawals->status = '0';
}
$ha_withdrawals->admin_feedback = $data->admin_feedback;
$ha_withdrawals->created_at = $data->created_at;
$ha_withdrawals->updated_at = $data->updated_at;
 
// update the ha_withdrawals
if($ha_withdrawals->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_withdrawals, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_withdrawals","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_withdrawals. Data is incomplete.","data"=> ""));
}
?>
