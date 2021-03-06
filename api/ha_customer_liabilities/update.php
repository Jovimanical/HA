<?php
// required headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: PUT, POST, OPTIONS');
    header("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_customer_liabilities.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare ha_customer_liabilities object
$ha_customer_liabilities = new Ha_Customer_Liabilities($db);

// get id of ha_customer_liabilities to be edited
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// set ID property of ha_customer_liabilities to be edited


if (!isEmpty($data->liabilityname) && !isEmpty($data->customerLiability)) {
// set ha_customer_liabilities property values
    $lastInsertedId = false;
    foreach ($data->customerLiability as $element) {
        // set ha_customer_liabilities property values
        if ($element->id == 0) {
            if (!isEmpty($element->accountNumber)) {
                $ha_customer_liabilities->accountNumber = $element->accountNumber;
            } else {
                $ha_customer_liabilities->accountNumber = '';
            }
            if (!isEmpty($element->balance)) {
                $ha_customer_liabilities->balance = $element->balance;
            } else {
                $ha_customer_liabilities->balance = '0.00';
            }
            if (!isEmpty($element->description)) {
                $ha_customer_liabilities->description = $element->description;
            } else {
                $ha_customer_liabilities->description = 'no description supplied';
            }
            if (!isEmpty($element->monthlyPayment)) {
                $ha_customer_liabilities->monthlyPayment = $element->monthlyPayment;
            } else {
                $ha_customer_liabilities->monthlyPayment = '0.00';
            }

            $ha_customer_liabilities->user_id = $profileData->id;
            $ha_customer_liabilities->liability_status = 'ACTIVE';
            $ha_customer_liabilities->liabilityType = $element->liabilityType;
            $ha_customer_liabilities->updatedAt = date('Y-m-d H:m:s');
            $lastInsertedID = $ha_customer_liabilities->create();
            $lastInsertedId = !($lastInsertedID == 0);

        } else {
            if (!isEmpty($element->accountNumber)) {
                $ha_customer_liabilities->accountNumber = $element->accountNumber;
            } else {
                $ha_customer_liabilities->accountNumber = 'NOT-PROVIDED';
            }
            if (!isEmpty($element->balance)) {
                $ha_customer_liabilities->balance = $element->balance;
            } else {
                $ha_customer_liabilities->balance = '0.00';
            }
            if (!isEmpty($element->description)) {
                $ha_customer_liabilities->description = $element->description;
            } else {
                $ha_customer_liabilities->description = 'NOT PROVIDED';
            }
            if (!isEmpty($element->monthlyPayment)) {
                $ha_customer_liabilities->monthlyPayment = $element->monthlyPayment;
            } else {
                $ha_customer_liabilities->monthlyPayment = '0.00';
            }
            $ha_customer_liabilities->liability_status = $element->liability_status;
            $ha_customer_liabilities->liabilityType = $element->liabilityType;
            $ha_customer_liabilities->updatedAt = date('Y-m-d H:m:s');
            $ha_customer_liabilities->user_id = $profileData->id;
            $ha_customer_liabilities->id = $element->id;
            $lastInsertedId = $ha_customer_liabilities->update();
        }
    }
// update the ha_customer_liabilities
    if ($lastInsertedId) {
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Updated Successfully", "data" => ""));
    } // if unable to update the ha_customer_liabilities, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_customer_liabilities", "data" => ""));

    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to update ha_customer_liabilities. Data is incomplete.", "data" => ""));
}
?>
