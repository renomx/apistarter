<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set("UTC");
require_once __DIR__ . "/../orm/Config.php";
require_once __DIR__ . "/../../vendor/autoload.php";
use App\Services\Env;
Env::init();

class Broadcast
{
	public $promo_id;
	public $msg;
	public $type;

	public function __construct($type, $msg, $promo_id)
	{
		$this->promo_id = isset($promo_id) ? $promo_id : null;
		$this->msg = isset($msg) ? $msg : null;
		$this->type = isset($type) ? $type : null;

		switch($this->type)
		{
			case "promo":
			{
				$this->sendPromo();
			}
			case "message":
			{
				$this->sendMessage();
			}
		}
	}

	public function sendPromo()
	{
		$promo = R::findOne('promo', 'id = ?', [$promo_id]);
		$devices = R::findAll('device');
		foreach($devices as $device)
		{
			$user = R::findOne('user', 'id = ?', [$device->user_id]);
			$msg = isset($user) ? $user->name . " esta promoción podría interesarte, " . $promo->title : "Esta promoción podría interesarte, " . $promo->title;
			Notification::sendPush(
				$device->token,
				$msg,
				["user_id" => $user->id, "promo_id" => $promo->id],
				0
			);
		}
	}	

	public function sendMessage()
	{
		echo "Sending a message to all registered devices \r\n";
		$devices = R::findAll('device');
		foreach($devices as $device)
		{	
			echo "Sending message " . $this->msg . " to device " . $device->token . "\r\n";		
			Notification::sendPush(
				$device->token,
				$this->msg,
				["msg" => "Broadcast testing message"],
				0
			);
		}
	}
}

$type = isset($argv[1]) ? $argv[1] : null;
$msg = isset($argv[2]) ? $argv[2] : null;
$id  = isset($argv[3]) ? $argv[3] : null;

$b = new Broadcast($type,$msg,$id);



