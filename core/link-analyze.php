<?php
/**
 * Check the given URL pattern against the current path and update global root URL route.
 *
 * @param string $urls Pattern to match URL against.
 * @return array|null Array of parameters if match is found, null otherwise.
 */
function urlCheck($urls)
{
    global $root;

    $patterns = [
        "{mix}" => "([\w-]+)",
        "{int}" => "([\d]+)",
    ];

    // Build regex pattern from URL patterns
    $regex = str_replace(array_keys($patterns), array_values($patterns), $urls);

    // Match the constructed regex pattern against the current URL path
    if (preg_match("@^$regex$@", $root['url']['path'], $parameters)) {

        // Initialize and populate the route information only if parameters are found
        $root['url']['route'] = [
            'url' => $urls,
            'params' => $parameters,
        ];

        // Return the matched parameters
        return $parameters;
    }

    // Return null if no match is found
    return null;
}

//---counter wordpress requests
$wp_links = ['wp-admin', 'wp-includes', 'wp-content', 'wp-login', 'inputs', '.git'];

$escapedLinks = array_map(function ($link) {
    return preg_quote($link, '/');
}, $wp_links);

$pattern = '/' . implode('|', $escapedLinks) . '/';
if (preg_match($pattern, $root['url']['path']) === 1) {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Bamboozled</title>';
    echo '<style>';
    echo '  body, html {';
    echo '    margin: 0;';
    echo '    padding: 0;';
    echo '    height: 100%;';
    echo '    overflow: hidden;';
    echo '  }';
    echo '  .bg-fullscreen {';
    echo '    background-image: url("/assets/default-client/images/wordpress_bamboozle.jpg");';
    echo '    background-size: cover;';
    echo '    background-position: center;';
    echo '    position: fixed;';
    echo '    top: 0;';
    echo '    right: 0;';
    echo '    bottom: 0;';
    echo '    left: 0;';
    echo '  }';
    echo '</style>';
    echo '</head>';
    echo '<body>';
    echo '  <div class="bg-fullscreen"></div>';
    echo '</body>';
    echo '</html>';
    exit;
}

//---get latest version
$item = array();
$item['table'] = 'inf_versions';
$item['where'][] = "version_id IS NOT NULL";
$item['order'][] = "version_id DESC";
$item['limit'] = "LIMIT 1";
$root['version'] = $db->query_read($item)['data'];
if (count($root['version'])) {
    $root['version'] = $root['version'][0];
}

//---get all links
$item = array();
$item['table'] = 'set_links';
$item['cache'] = false;
$item['resultby'] = "link_link";
$item['where'][] = "link_status IN('published')";
$item['limit'] = 'LIMIT 0,300';
$links = $db->query_read($item);

//---check if requested path is one of the $links available.
foreach ($links['data'] as $url => $item) {
    urlCheck($url);
    if (isset($root['url']['route'])) {
        break;
    }
}

//---get the default schema of `set_links` if url was not a match.
if (isset($root['url']['route'])) {
    $result['data'] = $item;
} else {
    $result = $db->query_read_schemas(["table_names" => ['set_links'], 'debugSQL' => true]);

    if (isset($result['set_links']) && count($result['set_links']['data'])) {
        $columnNames = array_column($result['set_links']['data'], 'COLUMN_NAME');
        $columnDefaults = array_column($result['set_links']['data'], 'COLUMN_DEFAULT');
        $result['data'] = array_combine($columnNames, $columnDefaults);
    }
}

$root['link'] = $result['data'];
unset($links, $result);

if ($root['link']['link_admin'] == 1) {
    $template = $settings['admin']['template'];
} else if (string_contains($root['link']['link_link'], "/documentation/")) {
    $template = $settings['documentation']['template'];
} else {
    $template = $settings['site']['template'];
}