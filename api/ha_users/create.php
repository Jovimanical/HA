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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_accounts.php';

use \Mailjet\Resources;
use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

$authentication_server_link = $_ENV['HA_APP_ENV'] == 'PROD' ? $_ENV['HA_EMAIL_VERIFICATION_LIVE_SERVER_LINK'] : $_ENV['HA_EMAIL_VERIFICATION_DEMO_SERVER_LINK'];


$database = new Database();
$db = $database->getConnection();

$ha_users = new Ha_Users($db);
$ha_accounts = new Ha_Accounts($db);
// get posted data
//$data = file_get_contents("php://input") !== false ? json_decode(file_get_contents("php://input")) : (object)$_REQUEST; //
$data = (json_decode(file_get_contents("php://input"), true) === NULL) ? (object)$_REQUEST : json_decode(file_get_contents("php://input"));

$mj = new \Mailjet\Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);


// make sure data is not empty

if (!isEmpty($data->firstname) && !isEmpty($data->email) && !isEmpty($data->password)
    && !isEmpty($data->lastname) && !isEmpty($data->mobile)) {

    // set ha_users property values
    if (!preg_match("/^[a-zA-Z ]*$/", $data->firstname)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid FirstName! Only letters and white space allowed.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $ha_users->firstname = htmlspecialchars(strip_tags($data->firstname));
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $data->lastname)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid LastName! Only letters and white space allowed.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $ha_users->lastname = htmlspecialchars(strip_tags($data->lastname));
    }
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid Email! Email format is invalid.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        // check if Email Exist in the database
        if ($ha_users->CheckEmailExist($data->email)) {
            $error_message = array(
                "status" => "error",
                "code" => 0,
                "message" => 'An Account User with this email already exist! please try another or login',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($error_message);
            return;
        } else {
            $ha_users->email = $data->email;
        }
    }

    if (!preg_match("/^[0-9]{11}+$/", $data->mobile)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Invalid Phone number! Only 10-digit mobile numbers allowed.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        if ($ha_users->CheckMobileExist($data->mobile)) {
            $error_message = array(
                "status" => "error",
                "code" => 0,
                "message" => 'An Account User with this Mobile Number already exist! please try another or login',
                "time" => date('Y-m-d')
            );
            http_response_code(400);
            echo json_encode($error_message);
            return;
        } else {
            $ha_users->mobile = htmlspecialchars(strip_tags($data->mobile));
        }
    }

    if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=??!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=??!\?]{6,20}$/", $data->password)) {
        $error_message = array(
            "status" => "error",
            "code" => 0,
            "message" => 'Password should be between 6 and 20 characters long, contains at least one special character, lowercase, uppercase and a digit.',
            "time" => date('Y-m-d')
        );
        http_response_code(400);
        echo json_encode($error_message);
        return;
    } else {
        $options = [
            'cost' => 12,
        ];
        $ha_users->password = password_hash($data->password, PASSWORD_BCRYPT, $options);
    }

    $ha_users->country_code = $data->country_code ?? 234;
    $ha_users->username = generate_username($ha_users->firstname . $ha_users->lastname, 30);
    $ha_users->profileImage = "https://via.placeholder.com/640x640.png?text=$ha_users->firstname+$ha_users->lastname";
    $ha_users->address = '';
    $ha_users->email_verified = 1;
    $ha_users->sms_verified = 0;

    // Generate random activation token
    $token = md5(rand() . time()) . uniqid();
    $ha_users->verification_code = $token;
    $ha_users->verification_code_send_at = date('Y-m-d H:m:s');
    $ha_users->status = 1;
    $ha_users->two_factor_status = 0;
    $ha_users->two_factor_verified = 1;


    $ha_users->roles = 'user';
    $ha_users->remember_token = 'false';
    $ha_users->created_at = date('Y-m-d H:m:s');
    $ha_users->updated_at = date('Y-m-d H:m:s');
    $lastInsertedId = $ha_users->create();

    // create the ha_users
    if ($lastInsertedId != 0) {
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

        //Create Account Wallet
        $ha_accounts->user_id = $lastInsertedId;
        $ha_accounts->account_number = $data->mobile;
        $ha_accounts->account_status = 'active';
        $ha_accounts->account_type = 'wallet';
        $ha_accounts->account_balance = 0.00;
        $ha_accounts->account_point = 0.00;
        $ha_accounts->account_blockchain_address = $wallet_address !== 0 ? $wallet_address : '';
        $ha_accounts->account_private_key = $wallet_private_key !== 0 ? $wallet_private_key : '';
        $ha_accounts->account_primary = 1;
        $ha_accounts->updatedAt = date('Y-m-d H:m:s');
        $lastAccountInsertedId = $ha_accounts->create();

        $sign_up_message = array("status" => "success", "code" => 1, "message" => "Account Created Successfully! A verification link has been sent to your email $ha_users->email", "data" => $lastInsertedId);

        $authentication_web_link = $authentication_server_link . '/' . $token . '/' . $data->email;
        $output = '<p>Dear ' . $data->firstname . '' . $data->lastname . ',</p>';
        $output .= '<p>Please click on the following link to Activate your account.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a style="background-color: #4CAF50;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;" href="' . $authentication_web_link . '" target="_blank"> Click to Verify your Email</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Or just copy and past this code into the form currently displayed</p>';
        $output .= '<p>Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reason.</p>';
        $output .= '<p>Thanks,</p>';
        $output .= '<p>HA SUPPORT TEAM</p>';
        $body = $output;
        $subject = "Account Activation Required - HouseAfrica.io";
        $RECIPIENT_EMAIL = $data->email;
        $RECIPIENT_FULLNAME = $data->firstname . ' ' . $data->lastname;
        $SENDER_EMAIL = 'no_reply@houseafrica.io';

        try {
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "$SENDER_EMAIL",
                            'Name' => "HA SUPPORT-TEAM"
                        ],
                        'To' => [
                            [
                                'Email' => "$RECIPIENT_EMAIL",
                                'Name' => "$RECIPIENT_FULLNAME"
                            ]
                        ],
                        'Subject' => $subject,
                        'TextPart' => 'Please copy and past link below on a browser,' . $authentication_web_link,
                        'HTMLPart' => $body
                    ]
                ]
            ];

            // All resources are located in the Resources class
            $response = $mj->post(Resources::$Email, ['body' => $body]);

            // Read the response
            $response->success();
            //Send Account Activation Email
            //Create an instance; passing `true` enables exceptions
        } catch (Exception $e) {
            $sign_up_message["email_message"] = "Message could not be sent. Mailer Error: {$e->errorMessage()}"; //Catch errors from Amazon SES.
        }

        // set response code - 201 created
        http_response_code(201);
        // tell the user
        echo json_encode($sign_up_message);
    } // if unable to create the ha_users, tell the user
    else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_users", "data" => ""));
    }
} // tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create ha_users. Data is incomplete.", "data" => ""));
}
?>
