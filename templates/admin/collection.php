<?php
if (file_exists((dirname(__FILE__)) . "/pages/" . $root['link']['link_page'])) {
    if (!can("show")) {
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $json['status'] = "error";
            $json['text'] = $lang['warnings']['errorSessionTimeOutYouMustLogIn'];
            $json['script'][] = "if(toastr){toastr.clear();}";
            $json['script'][] = "$('#modalLogin').modal('show');";
            $json['script'][] = "sessionErrorMessage();";
            dd(($json));
        } else {
            $_SESSION['messages']['danger'][] = $lang['warnings']['You dont have permission to access this page!'];
            header("Location: /?return=" . $root['url']['path'] . "");
            exit;
        }
    }
}

if (strpos($root['url']['query'], 'TESTME') !== false) {
    require_once ($_SERVER["DOCUMENT_ROOT"] . "/test/__test.php");
}

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/controllers/" . $root['link']['link_page'])) {
    require_once ($_SERVER["DOCUMENT_ROOT"] . "/controllers/" . $root['link']['link_page']);
}

$item = array();
$item['path'] = dirname(__FILE__);
$item['file'] = $root['link']['link_page'];

if (file_exists($item['path'] . "/pages/" . $item['file'])) {
    $page = renderView($item);
} else {
    header("HTTP/1.0 404 Not Found");
    $item['file'] = "404.php";
    $page = renderView($item);
}


echo ($page);
