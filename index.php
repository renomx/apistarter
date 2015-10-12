<?php
//Show errors  
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Set Timezone
date_default_timezone_set("UTC");

// ORM configuration
require_once "app/orm/Config.php";

// Composre autoloader
require_once "vendor/autoload.php";

// Dependencies
use Luracast\Restler\Restler;
use Luracast\Restler\Defaults;
use Luracast\Restler\Resources;
use Luracast\Restler\Format\HtmlFormat;

// Enable CORS by default
Defaults::$crossOriginResourceSharing = true;

// Supported formats by default
$r = new Restler();
$r->setSupportedFormats('JsonFormat', 'YamlFormat', 'XmlFormat', 'HtmlFormat', 'UploadFormat');

// Add endpoints here like (go into app directory there is the class for the endpoint):
$r->addAPIClass('Demo');

// For documentation
$r->addApiClass('Resources');
 
$r->handle();