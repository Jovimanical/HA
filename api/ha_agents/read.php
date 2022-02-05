<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_agents.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_agents object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_agents = new Ha_Agents($db);

$ha_agents->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_agents->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_agents will be here

// query ha_agents
$stmt = $ha_agents->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_agents array
    $ha_agents_arr=array();
	$ha_agents_arr["pageno"]=$ha_agents->pageNo;
	$ha_agents_arr["pagesize"]=$ha_agents->no_of_records_per_page;
    $ha_agents_arr["total_count"]=$ha_agents->total_record_count();
    $ha_agents_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_agents_item=array(
            
"id" => $id,
"firstname" => $firstname,
"lastname" => $lastname,
"username" => $username,
"email" => $email,
"country_code" => $country_code,
"mobile" => $mobile,
"balance" => $balance,
"password" => html_entity_decode($password),
"image" => html_entity_decode($image),
"address" => $address,
"status" => $status,
"ev" => $ev,
"sv" => $sv,
"ver_code" => $ver_code,
"ver_code_send_at" => $ver_code_send_at,
"ts" => $ts,
"tv" => $tv,
"roles" => html_entity_decode($roles),
"featured" => $featured,
"remember_token" => html_entity_decode($remember_token),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_agents_arr["records"], $ha_agents_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_agents data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_agents found","data"=> $ha_agents_arr));
    
}else{
 // no ha_agents found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_agents found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_agents found.","data"=> ""));
    
}
 


