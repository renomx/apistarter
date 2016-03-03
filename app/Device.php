<?php

use Luracast\Restler\RestException;
use App\Services\Env;
Env::init();
class Device
{
	/**
	 * Get all devices
	 *
	 * It retrieves all registered devices with all properties
	 *
	 * @return array
	 *
	 */
	function index()
	{
		$devs = R::find('device'); 
		return array_values($devs);
	}


	/**
	 * Get device information 
	 *
	 * Based on device id it retrieves all device information
	 *
	 * @param int $id Device id	
	 *
	 * @return array
	 *
	 */
	function get($id)
	{
		$device = R::findOne('device', 'id = ?', array($id));
		return $device;
	}

	/**
	 * Test push to one device
	 *
	 * @param string $device_token Device token
	 *
	 * @return array
	 *
	 */
	function getTestPush($device_token)
	{
		Notification::sendPush(
			$device_token,  
			"[" . date("Y-m-d H:i:s") . "] - Juanito Pérez, bienvenido ",
			["Datos" => "datos_random"], 
			5);							
	}

	/**
	 * Test broadcast to all devices
	 *
	 * @param string $message Message intended to broadcast
	 *
	 * @return array
	 *
	 */
	function getTestBroadcast($message)
	{
		shell_exec(Env::$config->phpPath.' '.Env::$config->servicesPath.'Broadcast.php message "' . $message .  '" >> '.Env::$config->logPath.'broadcasts.log &');
	}

	/**
	 * Get notification status
	 *
	 * Based on device id it will retrieve the notification status for a specific device
	 *
	 * @param string $token Device token
	 *
	 * @return array
	 *
	 */
	function getStatus($token)
	{
		$device = R::findOne('device','token = ?', array($token));
		if(isset($device))
		{
			return $device;
		}
		else
		{
			throw new RestException(404, 'Device not found!');
		}
	}


	/**
	 * Create a new device
	 *
	 * Create a new device using the token
	 *
	 * @param string $token Device token
	 *
	 * @return array
	 *
	 */
	public function post($device_token)
	{
		$dev = R::findOne('device', "token = ?", [$device_token]);
		if(count($dev) == 0)
		{
			$device = R::dispense('device');
			$device->token = $device_token;
			$device->notifications = true;
			$device->created_at = date("Y-m-d H:i:s");
			$device->updated_at = date("Y-m-d H:i:s");
			R::store($device);
			return $device;
		}
		else
		{
			return $dev;
		}
		
	}

	/**
	 * Updates device info
	 *
	 * Based on devices token it will update it's info like Token or if notifications are on or off
	 *
	 * @param string $device_token the device token to be registered
	 * @param bool $notifications Specifies if this device will receive push notifications or not
	 *
	 * @return mixed
	 */
	public function put($device_token, $notifications=null)
	{
		$dev = R::findOne('device', 'token = ?', array($device_token));
		$dev->notifications = isset($notifications) ? $notifications : $dev->notifications;
		$dev->updated_at = date("Y-m-d H:i:s");
		R::store($dev);
		return $dev;
	}

	/**
	 * Deletes a device
	 *
	 * Based on device_token it will delete it
	 *
	 * @param $device_token Device token
	 *
	 * @return mixed
	 */
	public function putDeleteDevice($device_token)
	{
		$dev = R::findOne('device', "token = ?", array($device_token));
		R::trash($dev);
	}
}