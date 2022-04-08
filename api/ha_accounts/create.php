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
use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_accounts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ha_accounts = new Ha_Accounts($db);

// get posted data
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));


// make sure data is not empty
if (!isEmpty($data->account_number)) {

    // set ha_accounts property values

    $ha_accounts->account_number =  filter_var($data->account_number, FILTER_SANITIZE_STRING);

    if ($ha_accounts->accountNumberExists()) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Account Number Already in Use by another!',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    }

    $wallet_address = 0;
    $wallet_private_key = 0;

    $config = [
        'private_key_type' => OPENSSL_KEYTYPE_EC,
        'curve_name' => 'secp256k1'
    ];

    $res = openssl_pkey_new($config);
    if ($res) {
        openssl_pkey_export($res, $priv_key);
        $key_detail = openssl_pkey_get_details($res);
        $pub_key = $key_detail["key"];
        $priv_pem = PEM::fromString($priv_key);
        $ec_priv_key = ECPrivateKey::fromPEM($priv_pem);
        $ec_priv_seq = $ec_priv_key->toASN1();
        $priv_key_hex = bin2hex($ec_priv_seq->at(1)->asOctetString()->string());
        $priv_key_len = strlen($priv_key_hex) / 2;
        $pub_key_hex = bin2hex($ec_priv_seq->at(3)->asTagged()->asExplicit()->asBitString()->string());
        $pub_key_len = strlen($pub_key_hex) / 2;
        $pub_key_hex_2 = substr($pub_key_hex, 2);
        $pub_key_len_2 = strlen($pub_key_hex_2) / 2;
        $hash = Keccak::hash(hex2bin($pub_key_hex_2), 256);

        $wallet_address = '0x' . substr($hash, -40);
        $wallet_private_key = '0x' . $priv_key_hex;

    }

    $ha_accounts->account_status = 'active';
    $ha_accounts->account_type = 'wallet';
    $ha_accounts->account_balance = 0.00;
    $ha_accounts->account_point = 0.00;
    $ha_accounts->account_blockchain_address = $wallet_address !== 0 ? $wallet_address :'';
    $ha_accounts->account_private_key = $wallet_private_key !== 0 ? $wallet_private_key :'';
    $ha_accounts->account_primary = 1;
    $ha_accounts->updatedAt = date('Y-m-d H:m:s');
    $ha_accounts->user_id =  $profileData->id;
    $lastInsertedId = $ha_accounts->create();
    // create the ha_accounts
    if ($lastInsertedId != 0) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Created Successfully", "data" => $lastInsertedId));
    } // if unable to create the ha_accounts, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_accounts", "data" => ""));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_accounts. Data is incomplete.", "data" => ""));
}
?>
