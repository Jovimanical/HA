<?php
// required headers
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

$database = new Database();
$db = $database->getConnection();

$ha_customer_other_income = new Ha_Customer_Other_Income($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// make sure data is not empty
if (!isEmpty($data->extraIncomename) && !isEmpty($data->customerAdditional)) {

    // set ha_customer_other_income property values
    $lastInsertedId = 0;
    foreach ($data->customerAdditional as $element) {

        if (!isEmpty($element->description)) {
            $ha_customer_other_income->description = $element->description;
        } else {
            $ha_customer_other_income->description = '';
        }
        if (!isEmpty($element->otherIncomeAmount)) {
            $ha_customer_other_income->otherIncomeAmount = $element->otherIncomeAmount;
        } else {
            $ha_customer_other_income->otherIncomeAmount = '0.00';
        }
        if (!isEmpty($element->otherIncomePeriod)) {
            $ha_customer_other_income->otherIncomePeriod = $element->otherIncomePeriod;
        } else {
            $ha_customer_other_income->otherIncomePeriod = 'Annual';
        }
        if (!isEmpty($element->otherIncomeType)) {
            $ha_customer_other_income->otherIncomeType = $element->otherIncomeType;
        } else {
            $ha_customer_other_income->otherIncomeType = 'otherincome';
        }

        $ha_customer_other_income->user_id = $profileData->id;
        $ha_customer_other_income->updatedAt = date('Y-m-d H:m:s');
        $lastInsertedId = $ha_customer_other_income->create();
        // create the ha_customer_other_income
    }

    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
    } // if unable to create the ha_customer_other_income, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_customer_other_income", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_customer_other_income. Data is incomplete.", "data" => ""));
}
?>
