<?php
error_reporting(E_ALL);
require_once '../token.php';
$website = "https://api.telegram.org/bot" . $botToken;
$update = file_get_contents("php://input");
$updateArray = json_decode($update, TRUE);
$chatId = $updateArray["message"]["chat"]["id"];
$message = $updateArray["message"]["text"];
$id = $updateArray["message"]["from"]["id"];
function sendMessage($chatId, $message) {
    $url = $GLOBALS[website]."/sendmessage?chat_id=".$chatId."&text=".urlencode($message);
    file_get_contents($url);
}
set_time_limit(0);
$folder = "";
ini_set('max_execution_time', 0);
//require the downloadfile class
require_once("curl.php");
//create an instance of it
$save = new downloadFile();
//get the size of the file to be copied
$filesize = $save->getSize($message);
sendMessage ($chatId, "File Size in MB: " . round($filesize / 1024 / 1024, 2));
sendMessage ($chatId, "Downloading...");
https://aminvakil.com/mba/%D8%A8%D8%B1%D9%86%D8%AF%DB%8C%D9%86%DA%AF%20-%20%D8%AC%D9%84%D8%B3%D9%87%201%20-%2097.3.31.mp3
//here is where we actually copy the file
$returnData = $save->saveFile($message,$folder);
//next lets log the download to a file called "logs.php.inc"
$save->logDownload($returnData,'log.html',$updateArray);
sendMessage ($chatId, "Download Complete!");
sendMessage ($chatId, "Uploading...");
$post = array('chat_id' => $GLOBALS["chatId"],'document'=>new CurlFile($returnData[3]));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot" . $GLOBALS["botToken"] . "/sendDocument");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt( $ch, CURLOPT_NOBODY, true );
curl_setopt( $ch, CURLOPT_HEADER, true );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_exec ($ch);
if (curl_error($ch))
	sendMessage($chatId, curl_error($ch));
curl_close ($ch);
?>
