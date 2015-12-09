<?php

require_once "rb.php";
$config = json_decode(file_get_contents(__DIR__. "/../../.config/database.json"));
R::setup($config->driver.':host='.$config->host.';dbname='.$config->dbname,$config->user,$config->pass);
R::useWriterCache(true);
