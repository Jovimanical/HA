<?php
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: *");
    header("HTTP/1.1 200 OK");
    die();
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../../vendor/autoload.php';


//require 'PHPMailer/Exception.php';
//require 'PHPMailer/PHPMailer.php';
//require 'PHPMailer/SMTP.php';

include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->body)
    && !empty($data->to)
    && !empty($data->subject)
) {

    if (sendEmail($data->to, $data->cc, $data->subject, $data->body)) {

        http_response_code(200);
        echo json_encode(array("status" => "success", "code" => 1, "message" => "Email sent Successfully", "data" => ""));
    } else {
        http_response_code(503);
        echo json_encode(array("status" => "error", "code" => 0, "message" => "Email sent failed!", "data" => ""));
    }

}// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("status" => "error", "code" => 0, "message" => "Unable to create order_sum. Data is incomplete.", "data" => ""));
}

function sendEmail($to, $cc, $subject, $body)
{


    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->IsSMTP();
        $mail->PluginDir = "../";
        $mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only

        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $_ENV['HA_SMTP_SERVER'] || "smtp.google.com"; // sets the SMTP server
        $mail->Port = 465;                    // set the SMTP port for the GMAIL server
        $mail->Username = $_ENV['HA_SMTP_USERNAME']|| "noreply@email.com"; // SMTP account username
        $mail->Password = $_ENV['HA_SMTP_PASSWORD'] || "PASSWORD";        // SMTP account password
        $mail->IsHTML(true);
        $mail->SetFrom('no_reply@houseafrica.com', 'SUPPORT-TEAM');

        //$mail->AddReplyTo("noreply@yourdomain.com","NoReply Name");

        $mail->Subject = $subject;

        //$mail->AltBody    = ""; // optional, comment out and test

        $mail->Body = $body;
        $toArray = explode(',', $to);
        foreach ($toArray as $toEmail) {
            $mail->AddAddress($toEmail);
        };

        if (!empty($cc)) {
            $ccArray = explode(',', $cc);
            foreach ($ccArray as $ccEmail) {
                $mail->AddCC($ccEmail);
            };

        }
        //$mail->Send();

        $mailStatus = "";
        //var_dump($mail->ErrorInfo);
        if (!$mail->Send()) {

            $mail->ClearAddresses();
            $mail->ClearAttachments();
            //$mailStatus= "Mailer Error "; $mail->ErrorInfo;
            return false;
        } else {
            //$mailStatus= "Message has been sent";
            return true;
        }

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }


}

?>
