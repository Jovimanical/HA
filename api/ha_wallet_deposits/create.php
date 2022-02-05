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
 
// instantiate ha_wallet_deposits object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_wallet_deposits.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_wallet_deposits = new Ha_Wallet_Deposits($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->user_id)
&&!isEmpty($data->method_code)
&&!isEmpty($data->amount)
&&!isEmpty($data->method_currency)
&&!isEmpty($data->charge)
&&!isEmpty($data->rate)
&&!isEmpty($data->final_amo)
&&!isEmpty($data->try)
&&!isEmpty($data->status)
&&!isEmpty($data->from_api)){
 
    // set ha_wallet_deposits property values
	 
if(!isEmpty($data->user_id)) { 
$ha_wallet_deposits->user_id = $data->user_id;
} else { 
$ha_wallet_deposits->user_id = '';
}
$ha_wallet_deposits->order_id = $data->order_id;
if(!isEmpty($data->method_code)) { 
$ha_wallet_deposits->method_code = $data->method_code;
} else { 
$ha_wallet_deposits->method_code = '';
}
if(!isEmpty($data->amount)) { 
$ha_wallet_deposits->amount = $data->amount;
} else { 
$ha_wallet_deposits->amount = '0.00000000';
}
if(!isEmpty($data->method_currency)) { 
$ha_wallet_deposits->method_currency = $data->method_currency;
} else { 
$ha_wallet_deposits->method_currency = '';
}
if(!isEmpty($data->charge)) { 
$ha_wallet_deposits->charge = $data->charge;
} else { 
$ha_wallet_deposits->charge = '0.00000000';
}
if(!isEmpty($data->rate)) { 
$ha_wallet_deposits->rate = $data->rate;
} else { 
$ha_wallet_deposits->rate = '0.00000000';
}
if(!isEmpty($data->final_amo)) { 
$ha_wallet_deposits->final_amo = $data->final_amo;
} else { 
$ha_wallet_deposits->final_amo = '0.00000000';
}
$ha_wallet_deposits->detail = $data->detail;
$ha_wallet_deposits->btc_amo = $data->btc_amo;
$ha_wallet_deposits->btc_wallet = $data->btc_wallet;
$ha_wallet_deposits->trx = $data->trx;
if(!isEmpty($data->try)) { 
$ha_wallet_deposits->try = $data->try;
} else { 
$ha_wallet_deposits->try = '0';
}
if(!isEmpty($data->status)) { 
$ha_wallet_deposits->status = $data->status;
} else { 
$ha_wallet_deposits->status = '0';
}
if(!isEmpty($data->from_api)) { 
$ha_wallet_deposits->from_api = $data->from_api;
} else { 
$ha_wallet_deposits->from_api = '0';
}
$ha_wallet_deposits->admin_feedback = $data->admin_feedback;
$ha_wallet_deposits->created_at = $data->created_at;
$ha_wallet_deposits->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_wallet_deposits->create();
    // create the ha_wallet_deposits
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_wallet_deposits, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_wallet_deposits","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_wallet_deposits. Data is incomplete.","data"=> ""));
}
?>
