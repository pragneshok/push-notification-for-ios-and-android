<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

$message = !empty($_REQUEST['msg']) ? $_REQUEST['msg'] : "This is a test message.";
$udid = !empty($_REQUEST['udid']) ? $_REQUEST['udid'] : "4d876306284d86881746592037521cd4a8981cf0e2294b99a84d111111111111";

if($message != "" && $udid != "")
{	
    $badge = 1;
    $sound = "default";
    $body = array();
    $body['aps']['alert'] = $message;				
    $body['aps']['badge'] = $badge;				
    $body['aps']['sound'] = $sound;
    $body['aps']['seat_id'] = 1;
    $body['aps']['push_type'] = 1;
    $payload = json_encode($body);	
   	
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'pem/apns-dev.pem');
    stream_context_set_option($ctx, 'ssl', 'verify_peer', false);	
    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
    if(!$fp)
    {
        echo "connection issue";
    }
    else
    {		
        $msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $udid)) . pack("n",strlen($payload)) . $payload;					
        if(fwrite($fp, $msg))
            echo "push sent successfully";
        else
            echo "push not sent successfully";
        fclose($fp);
    }    
}
?>
