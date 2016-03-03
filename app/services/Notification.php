<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use Monolog\Logger;
use App\Services\Env;

class Notification extends Logger
{ 
	public static function sendPush($deviceToken, $message, $data = null, $type = null)
	{	
		$log = new \Monolog\Logger('notifications');
		$log->pushHandler(new \Monolog\Handler\StreamHandler(Env::$config->logPath.'notifications.log', Logger::DEBUG));
		$log->addInfo('Entering to send a push');
		if(strlen($deviceToken) == 64)
		{
			$log->addInfo('Sending notification to ios device', ["deviceToken" => $deviceToken]);			

			$ctx = stream_context_create();

			//dev pem /home7/quesoazu/www/doyride/push/dev_key.pem
			stream_context_set_option($ctx, 'ssl', 'local_cert', Env::$config->pem);
			stream_context_set_option($ctx, 'ssl', 'passphrase', Env::$config->passphrase);

			// Open a connection to the APNS server
			// DEV APNS gateway.sandbox.push.apple.com:2195
			$fp = stream_socket_client(
				Env::$config->apns, $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);

			//echo 'Connected to APNS' . PHP_EOL;
			// Create the payload body
			$body['aps'] = array(
				'alert' => $message,
				'sound' => 'default',
				'content-available' => 1
				//'badge' => 0
				);
			$body['d'] = $data;
			$body['t'] = $type;

			// Encode the payload as JSON
			$payload = json_encode($body);		

			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));

			$log->addDebug($result);

			// Close the connection to the server
			fclose($fp);
		}
		else
		{
			$log->addInfo('Sending notification to android device', ["deviceToken" => $deviceToken]);
			//AIzaSyBpweimzrQ-5pjUO1absB4cTrDVRHIxmMg
			$api_key = Env::$config->apikey;
			//$registrationIDs = array("APA91bHOZnfPwVys28cus-w9s18zZw4lXb-CU1Os8OiA2MpLpvGc4b9sxipnAVZNiDHe3iWv4T-_5B7UHJ_ce2ybu_w_Z4Y_kXWsIJqE4bjyF0tcrZrofszmE42xJ_sg15Tw2yG2IxVXcFu37LyP7ZHx9DqRqqRByPSLUwkrUqzqavQSWt1A3l4");
			$registrationIDs = array($deviceToken);
			$message = $message;
			$url = 'https://android.googleapis.com/gcm/send';

			/*$body['registration_ids'] = $deviceToken;
			$body['aps'] = array(		                
		                'alert'             => $message,
		                'sound'				=> 'default'
		                );
			$body['d'] = $data;
			$body['t'] = $type;*/

			$fields = array(
                'registration_ids'  => $registrationIDs,
                'data'              => array( 
                								"message" => $message, 
                								"d" => $data,
                								"t" => $type
                						),
            );

		 
			$headers = array(
							'Authorization: key=' . $api_key,
							'Content-Type: application/json');
		 
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch);
			$log->addDebug($result);
			curl_close($ch);
		}		
	}

	public static function sendMain($recipient, $message)
	{
		$to = $recipient;
		$subject = "Autostop te da la bienvenida.";

		$headers = "From: hola@autostop.mx\r\n";
		$headers .= "MIME-version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

			
	}
}