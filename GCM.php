<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

$token = $_REQUEST["token"];
$push_message = $_REQUEST["message"];
$authorization_key = $_REQUEST["key"]; //You can get this key from google

function push_notification($registatoin_ids, $messageText) {
    
    $message['android']['title'] = $messageText;
    $message['android']['message'] = $messageText;
    $message['android']['icon'] = 'appicon';
    $message['android']['sound'] = 'default';
    $message['android']['vibrate'] = 'true';
    $message['android']['badge'] = '1';
    $message['android']['param1'] = '1';
    $message['android']['param2'] = '1';
	
    $url = 'https://android.googleapis.com/gcm/send';
	
    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message,
    );
	
    $headers = array(
        'Authorization: key='.$authorization_key,
        'Content-Type: application/json'
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));        
    }

    // Close connection
    curl_close($ch);
    echo $result;
}

$registatoin_ids    = array($token);
push_notification( $registatoin_ids, $push_message);

?>
