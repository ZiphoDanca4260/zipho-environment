<?php

use MatthiasMullie\Minify;

/**
 * Outputs the HTML tags for including CSS and JavaScript files based on their position.
 * It retrieves file information from a global `$root` array structure.
 *
 * @param string $position The position in the HTML document where the tags should be printed.
 *                         Typically 'header' or 'footer'. Defaults to 'header'.
 *
 * @global array $root Global array containing the links and assets to be included.
 *                     Specifically, this function reads from `$root['link']['link_js_css']`
 *                     where CSS and JS files are listed with their properties such as file path and attributes.
 *
 * @return void This function does not return a value. It echoes the output directly.
 */

function print_css_js($position = "header")
{
    global $root;
    $minified_key = "{$position}";
    // Check if the position has minified JS/CSS files to include and ensure it's an array.
    if (isset($root['link']['link_js_css'][$minified_key]) && is_array($root['link']['link_js_css'][$minified_key])) {
        foreach ($root['link']['link_js_css'][$minified_key] as $file) {
            // Process attributes if they exist and are an array
            $attrs = '';
            if (isset($file['attrs']) && is_array($file['attrs'])) {
                $attr_array = array_map(function ($obj) {
                    return "{$obj['name']}=\"{$obj['value']}\"";
                }, $file['attrs']);
                $attrs = implode(' ', $attr_array);
            }

            // Output the appropriate HTML tag based on file type.
            if ($file['type'] === 'css') {
                $defer_attr = ($position == 'footer') ? "defer " : "";
                echo "<link {$defer_attr}rel='stylesheet' type='text/css' href='{$file['file']}' $attrs media='all' />\n";
            } elseif ($file['type'] === 'js') {
                echo "<script src='{$file['file']}' $attrs></script>\n";
            }
        }
    }
}

/**
 * this function trigers each page loads and minify file depend on js css file change on production site
 * manuplate the $root['link']['link_js_css'] file structure depend on need.
 * @global array $root Global array containing the links and assets to be included.
 *                     Specifically, this function reads from `$root['link']['link_js_css']`
 *                     where CSS and JS files are listed with their properties such as file path and attributes.
 *
 * @return void This function does not return a value. Replaced with minfied file the $root['link']['link_js_css'] content.
 */
function merge_minify_scripts_css()
{
    global $root;

    // Ensure base directories exist
    $js_css_folder = "temp/css-js";
    makeFolder("$js_css_folder");
    makeFolder("settings/css-js");

    // Initialize or load link_js_css configuration
    $link_config_file = isset($root['link']['link_js_css']) ? $root['link']['link_js_css'] : "";

    $link_config_file = is_file($link_config_file) ? $link_config_file :
        "settings/css-js/" . $root['link']['link_admin'] .
        "-" . str_replace('.php', '.json', basename($root['link']['link_page']));

    if (!is_file($link_config_file)) {
        $link_config_file = "settings/css-js/" . (($root['link']['link_admin'] == 1) ? '1' : '0') . "-default.json";
    }

    $root['link']['link_js_css'] = is_file($link_config_file) ?
        json_decode(file_get_contents($link_config_file), true) : [];

    // Preview handling
    if (isset($_SESSION['preview_link_js_css'])) {
        $root['link']['link_js_css'] = $_SESSION['preview_link_js_css'];
        unset($_SESSION['preview_link_js_css']);
    }

    //---the default is minify.
    $should_minify = true;
    // Skip minification based on specified conditions
    if (strpos($_SERVER['REQUEST_URI'], 'UNMERGE') !== false || isDevopUser()) {
        $should_minify = false;
    } else if (strpos($_SERVER['REQUEST_URI'], 'MERGE') !== false) {
        $should_minify = true;
    }

    // Append developer resources if admin
    if ($root['user']['user_group_id'] == 1) {
        $root['link']['link_js_css']['footer'][] = array(
            "file" => "/global_assets/js/admin-developer.js",
            "merge" => true,
            "active" => true,
            "type" => "js"
        );
        $root['link']['link_js_css']['header'][] = array(
            "file" => "/global_assets/css/admin-developer.css",
            "merge" => true,
            "active" => true,
            "type" => "css"
        );
    }
    // if not ordered to minify the no need to go further
    if (empty($should_minify)) {
        return;
    }
    // Prepare script and style arrays for merging
    // Distrubute js/css files to header/footer 
    $merge_data = array();
    foreach ($root['link']['link_js_css'] as $position => $files) {
        foreach ($files as $file) {
            if (!isset($file['active']) || !$file['active']) {
                continue;
            }
            $file['real'] = realpath(ltrim($file['file'], '/'));
            $file['time'] = is_file($file['real']) ? filemtime($file['real']) : null;

            if ($should_minify && isset($file['merge']) && $file['merge']) {
                $merge_data[$position][$file['type']]['files'][] = $file;
                $merge_data[$position][$file['type']]['names'][] = $file['file'] . "?" . $file['time'];
            }
        }
        // Add hashed file names base on files for js file check
        if (isset($merge_data[$position]['js'])) {
            $merge_data[$position]['js']['md5'] = md5(implode('', $merge_data[$position]['js']['names'])) . ".js";
        }
        // Add hashed file names base on files for css file check
        if (isset($merge_data[$position]['css'])) {
            $merge_data[$position]['css']['md5'] = md5(implode('', $merge_data[$position]['css']['names'])) . ".css";
        }
    }

    // Create minified files
    $return = array();
    foreach ($merge_data as $position => $data) {
        // check if any file changed first
        foreach ($data as $type => $object) {
            // should we minify with new content 
            $minified_file_name = "$js_css_folder/{$object['md5']}";
            // if there is not minified file mark that we should create minify 
            if (!is_file($minified_file_name) || $should_minify) {
                $minifier = ($type == 'css') ? new Minify\CSS('') : new Minify\JS('');
                foreach ($object['files'] as $data_file) {
                    $minifier->add($data_file['real']);
                }
                file_put_contents($minified_file_name, $minifier->minify());
                $return[$position][] = array(
                    "file" => "/$minified_file_name",
                    "merge" => true,
                    "active" => true,
                    "type" => $type
                );
            }
        }
    }
    if (count($return)) {
        $root['link']['link_js_css'] = $return;
    }
}

// remove inactive languages
$item = array();
foreach ($settings['languages'] as $index => $language) {
    if ($language['active'])
        $item[] = $language;
}
$settings['languages'] = $item;

if (xGET('task') == "logout") {
    logOut();
}

clear_cache();
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == "noneajax") {
    $A1 = array();
    $A1['__token'] = __token();
    if ($root['user']['user_id']) {
        $A1['chat']['token'] = md5($settings['site']['salt'] . strtotime(date('Y-m-d')) . $root['user']['user_id']);
    }
    $A1['jwt'] = xCOOKIE('jwt');
    $A1['domain'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'];
    $A1['temp'] = $root['temp'];
    $A1['user'] = $root['user'];
    $A1['user_groups'] = $root['user_groups'];
    $A1['version'] = $root['version'];
    $A1['upload']['size'] = $settings['configuration']['upload']['size'];
    merge_minify_scripts_css();
}

//---include help if applicable
$item = array();
$item['table'] = 'inf_helps';
$item['db_conn_name'] = 'default';
if ($root['link']['link_admin']) {
    $item['where'][] = "help_render_to LIKE '%\"admin\"%' ";
} else {
    $item['where'][] = "help_render_to LIKE '%\"client\"%' ";
}
$item['where'][] = "(link_ids LIKE '%\"{$root['link']['link_id']}\"%' OR link_ids LIKE '%\"0\"%')";
$item['limit'] = "LIMIT 200";
$item['order'][] = "help_id ASC";
$helps = $db->query_read($item);
$A1['helps'] = $helps['data'];

//---include the countries
$item = [
    'selects' => ['country_id', 'country_iso2', 'country_name', 'country_phone_code', 'country_phone_area_code_pattern_regex', 'country_phone_number_pattern_regex'],
    'table' => 'set_countries',
    'limit' => 'LIMIT 0,300',
    'cache' => true
];
$A1['countries'] = $db->query_read($item)['data'];

//---send any messages on SESSION
if (!empty($_SESSION['message'])) {
    $A1['message'] = $_SESSION['message'];
    unset($_SESSION['message']);
}