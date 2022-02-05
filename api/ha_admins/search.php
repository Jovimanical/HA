<?php
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
// instantiate database and ha_admins object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_admins = new Ha_Admins($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_admins->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_admins->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_admins
$stmt = $ha_admins->search($searchKey);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_admins array
    $ha_admins_arr=array();
	$ha_admins_arr["pageno"]=$ha_admins->pageNo;
	$ha_admins_arr["pagesize"]=$ha_admins->no_of_records_per_page;
    $ha_admins_arr["total_count"]=$ha_admins->total_record_count();
    $ha_admins_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_admins_item=array(
            
"id" => $id,
"name" => $name,
"email" => $email,
"username" => $username,
"email_verified_at" => $email_verified_at,
"image" => html_entity_decode($image),
"password" => html_entity_decode($password),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_admins_arr["records"], $ha_admins_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_admins data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_admins found","data"=> $ha_admins_arr));
    
}else{
 // no ha_admins found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_admins found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_admins found.","data"=> ""));
    
}
 


