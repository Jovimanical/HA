<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Methods: GET,POST, OPTIONS');
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


$reference = isset($_GET['reference']) ? $_GET['reference'] : die();

if (!$reference) {
    die('No reference supplied');
}

// initiate the Library's Paystack Object
$paystack_key = trim($_ENV['HA_PAYSTACK_SECRET_KEY']);

$paystack = new Yabacon\Paystack($paystack_key);

try {
    // verify using the library// unique to transactions
    $paystack_transaction_response = $paystack->transaction->verify(['reference' => $reference,]);

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode(array("status" => "success", "code" => 1, "message" => "ha_transactions found", "data" => $paystack_transaction_response));


} catch (\Yabacon\Paystack\Exception\ApiException $e) {
    print_r($e->getResponseObject());
    http_response_code(404);
    echo json_encode(array("status" => "error", "code" => 0, "message" => $e->getMessage(), "data" => ""));
}

//if ('success' === $tranx->data->status) {
//    // transaction was successful...
//    // please check other things like whether you already gave value for this ref
//    // if the email matches the customer who owns the product etc
//    // Give value
//}