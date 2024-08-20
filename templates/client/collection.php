<?php

$item = array();
$item['path'] = dirname(__FILE__);
$item['file'] = $root['link']['link_page'];
$link_page_file_path = dirname(__FILE__) . '/pages/' . $root['link']['link_page'];

if (empty($root['link']['link_page']) || !file_exists($link_page_file_path)) {
    header("HTTP/1.0 404 Not Found");
    $item['file'] = "404.php";
    $page = renderView($item);
    RE($page);
}

if (strpos($root['url']['query'], 'TESTME') !== false) {
    require_once ($_SERVER["DOCUMENT_ROOT"] . "/test/__test.php");
}

if (!empty($root['link']['link_page']) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/controllers/" . basename($root['link']['link_page']))) {
    require_once ($_SERVER["DOCUMENT_ROOT"] . "/controllers/" . basename($root['link']['link_page']));
}

$item = array();
$item['path'] = dirname(__FILE__);
$item['file'] = $root['link']['link_page'];
$page = renderView($item);
RE($page);