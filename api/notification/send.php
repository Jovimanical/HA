<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';

 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

//If you have your own FCM token or user table then use the below code to read data from your table.
//include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/FCMTable.php';
//$fcm = new FCMTable($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
!empty($data->title)
&&!empty($data->body)
&&!empty($data->token)
){
 
	$url = "https://fcm.googleapis.com/fcm/send";
    $token = $data->token; //"your device token";
    $serverKey = 'your server token of FCM project';
    $title =$data->title;
    $body = $data->body;
	$subtitle  = $data->subtitle;
	$iconURL=$data->iconURL;
	$iconImage=$data->iconImage;
    $notification = array('title' =>$title ,'subtitle'  => $subtitle , 'body' => $body, 'icon'  => $iconURL, 'image' => $iconImage, 'sound' => 'default', 'badge' => '1');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    $response = curl_exec($ch);
    if ($response === FALSE) {
		 http_response_code(503);
		 echo json_encode(array("status" => "error", "code" => 0,"message"=> "Failed to send notification","data"=> curl_error($ch)));
    
    }
    curl_close($ch);
	 http_response_code(201);
	 echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> ""));

}
else{

    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Data is incomplete.","data"=> ""));
}
?>

