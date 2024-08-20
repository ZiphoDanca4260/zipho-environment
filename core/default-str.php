<?php

$root['now'] = $now;

if (!isset($_SERVER['HTTP_REFERER'])) {
	$_SERVER['HTTP_REFERER'] = '';
}

if (xSESSION("get", ["users_user"]) == '') {
	$root['user']['user_user'] = xCOOKIE("user");
} else {
	$root['user']['user_user'] = xSESSION("get", ["users_user"]);
}

$root['url'] = parse_url(getFullUrl() . $_SERVER['REQUEST_URI']);

if (!isset($root['url']['query'])) {
	$root['url']['query'] = "";
}

parse_str($root['url']['query'], $root['url']['params']);

if (isset($_GET['quick_search'])) {
	$_GET['quick_search'] = clear_data($_GET['quick_search']);
}

if (isset($root['url']['params'])) {
	foreach ($root['url']['params'] as $key => $value) {
		$root['url']['params'][$key] = clear_data($value);
	}
}

$users_user = $root['user']['user_user'];
$root['vars'] = get_task_and_data();