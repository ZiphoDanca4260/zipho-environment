<?php

session_start();

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	$_SERVER['HTTP_X_REQUESTED_WITH'] = "noneajax";
}

include_once ($_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/core/base_model.php");

$settings = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/settings/settings.json'), true);
date_default_timezone_set($settings['configuration']['timeZone']['timeZone']);
$now = date('Y-m-d H:i:s');

try {
	$file = $_SERVER["DOCUMENT_ROOT"] . "/connection/db.json";

	if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == "::1") {
		$file = $_SERVER["DOCUMENT_ROOT"] . "/connection/db-local.json";
	}

	$db_construct = [
		'config_path' => "{$file}",
		'now' => $now,
		'cache_tiemout' => $settings['cache']['admin-SQL']['expire'],
		'debug_authorized_ips' => $settings['debugAuthorizedIPs']
	];

	$db = BaseModel::instance_get($db_construct);
} catch (\Throwable $th) {
	BaseModel::connection_error($th);
}

include_once ('default-str.php');

include_once ('link-analyze.php');

include_once ('user-str.php');

include_once ('language-selector.php');

//---------------------------------
// TIMEREND = ~40ms
//---------------------------------

include_once ($_SERVER['DOCUMENT_ROOT'] . "/controllers/default.php");

include_once ($template . "collection.php");