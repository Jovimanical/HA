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
 
// instantiate ha_general_settings object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_general_settings.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_general_settings = new Ha_General_Settings($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->ev)
&&!isEmpty($data->en)
&&!isEmpty($data->sv)
&&!isEmpty($data->sn)
&&!isEmpty($data->force_ssl)
&&!isEmpty($data->secure_password)
&&!isEmpty($data->agree)
&&!isEmpty($data->cod)
&&!isEmpty($data->registration)
&&!isEmpty($data->product_commission)
&&!isEmpty($data->seller_withdraw_limit)){
 
    // set ha_general_settings property values
	 
$ha_general_settings->sitename = $data->sitename;
$ha_general_settings->cur_text = $data->cur_text;
$ha_general_settings->cur_sym = $data->cur_sym;
$ha_general_settings->email_from = $data->email_from;
$ha_general_settings->email_template = $data->email_template;
$ha_general_settings->sms_api = $data->sms_api;
$ha_general_settings->base_color = $data->base_color;
$ha_general_settings->secondary_color = $data->secondary_color;
$ha_general_settings->mail_config = $data->mail_config;
$ha_general_settings->sms_config = $data->sms_config;
if(!isEmpty($data->ev)) { 
$ha_general_settings->ev = $data->ev;
} else { 
$ha_general_settings->ev = '0';
}
if(!isEmpty($data->en)) { 
$ha_general_settings->en = $data->en;
} else { 
$ha_general_settings->en = '0';
}
if(!isEmpty($data->sv)) { 
$ha_general_settings->sv = $data->sv;
} else { 
$ha_general_settings->sv = '0';
}
if(!isEmpty($data->sn)) { 
$ha_general_settings->sn = $data->sn;
} else { 
$ha_general_settings->sn = '0';
}
if(!isEmpty($data->force_ssl)) { 
$ha_general_settings->force_ssl = $data->force_ssl;
} else { 
$ha_general_settings->force_ssl = '0';
}
if(!isEmpty($data->secure_password)) { 
$ha_general_settings->secure_password = $data->secure_password;
} else { 
$ha_general_settings->secure_password = '0';
}
if(!isEmpty($data->agree)) { 
$ha_general_settings->agree = $data->agree;
} else { 
$ha_general_settings->agree = '0';
}
if(!isEmpty($data->cod)) { 
$ha_general_settings->cod = $data->cod;
} else { 
$ha_general_settings->cod = '1';
}
if(!isEmpty($data->registration)) { 
$ha_general_settings->registration = $data->registration;
} else { 
$ha_general_settings->registration = '0';
}
$ha_general_settings->active_template = $data->active_template;
if(!isEmpty($data->product_commission)) { 
$ha_general_settings->product_commission = $data->product_commission;
} else { 
$ha_general_settings->product_commission = '0.00';
}
if(!isEmpty($data->seller_withdraw_limit)) { 
$ha_general_settings->seller_withdraw_limit = $data->seller_withdraw_limit;
} else { 
$ha_general_settings->seller_withdraw_limit = '0.00000000';
}
$ha_general_settings->sys_version = $data->sys_version;
$ha_general_settings->created_at = $data->created_at;
$ha_general_settings->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_general_settings->create();
    // create the ha_general_settings
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_general_settings, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_general_settings","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_general_settings. Data is incomplete.","data"=> ""));
}
?>
