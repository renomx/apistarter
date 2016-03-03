<?php

namespace App\Services;

class Env {

	public static $config;
	public static $db;

	public static function init()
	{
		self::$config = json_decode(file_get_contents(__DIR__. "/../../.config/config.json"));
		self::$db = json_decode(file_get_contents(__DIR__."/../../.config/database.json"));
	}
}