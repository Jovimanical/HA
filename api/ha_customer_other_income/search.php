<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
            header('Access-Control-Allow-Credentials: true');
            header("HTTP/1.1 200 OK");
            return;
        }
    }
}


require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_customer_other_income.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

// instantiate database and ha_customer_other_income object
$database = new Database();
$db = $database->getConnection();

// initialize object
$ha_customer_other_income = new Ha_Customer_Other_Income($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ha_customer_other_income->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ha_customer_other_income->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

// query ha_customer_other_income
$stmt = $ha_customer_other_income->search($searchKey);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    //ha_customer_other_income array
    $ha_customer_other_income_arr = array();
    $ha_customer_other_income_arr["pageno"] = $ha_customer_other_income->pageNo;
    $ha_customer_other_income_arr["pagesize"] = $ha_customer_other_income->no_of_records_per_page;
    $ha_customer_other_income_arr["total_count"] = $ha_customer_other_income->total_record_count();
    $ha_customer_other_income_arr["records"] = array();

    // retrieve our table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $ha_customer_other_income_item = array(
            "id" => $id,
            "user_id" => $user_id,
            "description" => html_entity_decode($description),
            "otherIncomeAmount" => $otherIncomeAmount,
            "otherIncomePeriod" => $otherIncomePeriod,
            "otherIncomeType" => $otherIncomeType,
            "createdAt" => $createdAt,
            "updatedAt" => $updatedAt
        );

        array_push($ha_customer_other_income_arr["records"], $ha_customer_other_income_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show ha_customer_other_income data in json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_customer_other_income found", "data" => $ha_customer_other_income_arr));

} else {
    // no ha_customer_other_income found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no ha_customer_other_income found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "No ha_customer_other_income found.", "data" => ""));

}
 


