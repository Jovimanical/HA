<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: POST,GET, OPTIONS');
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_carts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
try {
// instantiate database and ha_carts object
    $database = new Database();
    $db = $database->getConnection();

// initialize object
    $ha_carts = new Ha_Carts($db);

    $ha_carts->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
    $ha_carts->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
// read ha_carts will be here
// set ID property of record to read
    $ha_carts->user_id = $profileData->id;

// query ha_carts
    $stmt = $ha_carts->read();
    $num = $stmt->rowCount();

// check if more than 0 record found
    if ($num > 0) {

        //ha_carts array
        $ha_carts_arr = array();
        $ha_carts_arr["pageno"] = $ha_carts->pageNo;
        $ha_carts_arr["pagesize"] = $ha_carts->no_of_records_per_page;
        $ha_carts_arr["total_count"] = $ha_carts->total_user_record_count();
        $ha_carts_arr["records"] = array();

        // retrieve our table contents

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $ha_carts_item = array(
                "id" => $id,
                "PropertyEstate" => $PropertyEstate,
                "PropertyBlock" => $PropertyBlock,
                "PropertyFloor" => $PropertyFloor,
                "PropertyId" => $PropertyId,
                "PropertyName" => html_entity_decode($PropertyName),
                "PropertyAmount" => $PropertyAmount,
                "MapSnapshot" => $MapSnapshot,
                "PaymentMethod" => $PaymentMethod,
                "PropertyJson" => $PropertyJson,
                "PropertyType" => $PropertyType,
                "PropertyStatus" => $PropertyStatus,
                "ApplicationStatus" => $ApplicationStatus,
                "user_id" => $user_id,
                "createdAt" => $createdAt
            );

            array_push($ha_carts_arr["records"], $ha_carts_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show ha_carts data in json format
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Cart Items found", "data" => $ha_carts_arr));

    } else {
        // no ha_carts found will be here

        // set response code - 404 Not found
        http_response_code(201);

        // tell the user no ha_carts found
        echo json_encode(array("status" => "success", "code" => 1, "message" => "No Carts Item found.", "data" => ""));

    }
} catch (Exception $exception) {
    http_response_code(404);
    // tell the user no ha_carts found
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Error Caught in transactions. " . $exception->getMessage(), "data" => ""));

}


