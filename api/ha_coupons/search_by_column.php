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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_coupons.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// instantiate database and ha_coupons object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$ha_coupons = new Ha_Coupons($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$ha_coupons->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_coupons->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_coupons
$stmt = $ha_coupons->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    //ha_coupons array
    $ha_coupons_arr=array();
	$ha_coupons_arr["pageno"]=$ha_coupons->pageNo;
	$ha_coupons_arr["pagesize"]=$ha_coupons->no_of_records_per_page;
    $ha_coupons_arr["total_count"]=$ha_coupons->search_record_count($data,$orAnd);
    $ha_coupons_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $ha_coupons_item=array(
            
"id" => $id,
"coupon_name" => $coupon_name,
"coupon_code" => $coupon_code,
"discount_type" => $discount_type,
"coupon_amount" => $coupon_amount,
"description" => $description,
"minimum_spend" => $minimum_spend,
"maximum_spend" => $maximum_spend,
"usage_limit_per_coupon" => $usage_limit_per_coupon,
"usage_limit_per_user" => $usage_limit_per_user,
"status" => $status,
"start_date" => $start_date,
"end_date" => $end_date,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($ha_coupons_arr["records"], $ha_coupons_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ha_coupons data in json format
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_coupons found","data"=> $ha_coupons_arr));
    
}else{
 // no ha_coupons found will be here

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ha_coupons found
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ha_coupons found.","data"=> ""));
    
}
 


