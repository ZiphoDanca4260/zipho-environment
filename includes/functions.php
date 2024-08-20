<?php

if (is_file($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php")) {
	require_once ($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
}

/**
 * Manages session operations such as get, put, and remove for a specific session key or keys.
 *
 * @param string $task The type of operation to perform ('get', 'put', 'remove').
 * @param mixed $key The session key(s) to operate on; can be an array of keys or a single key as a string.
 * @param mixed $item The item to put in the session if the task is 'put'.
 * @return mixed Returns the session value if task is 'get' and the key exists, otherwise null.
 */
function xSESSION($task = "get", $key = array(), $item = "")
{
	// Normalize $key into an array if it's provided as a string.
	$keys = is_array($key) ? $key : [$key];

	switch ($task) {
		case "get":
			// Attempt to return the session value for the first key if it exists.
			return $keys && isset($_SESSION[$keys[0]]) ? $_SESSION[$keys[0]] : null;

		case "put":
			foreach ($keys as $sessionKey) {
				$_SESSION[$sessionKey] = $item;
			}
			break;

		case "remove":
			foreach ($keys as $sessionKey) {
				unset($_SESSION[$sessionKey]);
			}
			break;
	}
}

/**
 * Retrieves the value of a specified cookie if it exists.
 *
 * @param string $cookieName Name of the cookie to retrieve.
 * @return mixed|null Value of the specified cookie, or null if not set.
 */
function xCOOKIE($cookieName)
{
	return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null;
}

/**
 * Constructs the full URL based on the current request, optionally with a subdomain.
 *
 * @param string $subdomain Optional subdomain to prepend to the URL.
 * @return string The full URL including the protocol, (optional) user, host, port, and path.
 */
function getFullUrl($subdomain = "")
{
	// Determine if the connection is secure
	$isHttps = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
		!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;

	// Construct protocol part
	$protocol = $isHttps ? 'https://' : 'http://';

	// Prepend subdomain if provided to SERVER_NAME and HTTP_HOST
	$serverName = $subdomain . $_SERVER['SERVER_NAME'];
	$httpHost = $subdomain . $_SERVER['HTTP_HOST'];

	// Assign new values to global server variable
	$_SERVER['SERVER_NAME'] = $serverName;
	$_SERVER['HTTP_HOST'] = $httpHost;

	// Determine the host part
	$host = isset($_SERVER['HTTP_HOST']) ? $httpHost : $serverName;
	// Check and append non-standard port if necessary
	$port = ($isHttps && $_SERVER['SERVER_PORT'] !== 443 ||
		!$isHttps && $_SERVER['SERVER_PORT'] !== 80) ? ':' . $_SERVER['SERVER_PORT'] : '';

	// User credential part if available
	$user = !empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '';

	// Constructing the base path from the SCRIPT_NAME
	$basePath = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));

	// Combine all parts to form the full URL
	return $protocol . $user . $host . $port . $basePath;
}

/**
 * Retrieves 'task' and '__token' from POST or GET requests, and processes 'data' from POST.
 * 'task' will first try to be read from POST, then GET. '__token' follows a similar pattern.
 * 'data' will be JSON-decoded if it is not an array.
 *
 * @return array Contains 'task', 'data', and '__token' from the requests.
 */
function get_task_and_data()
{
	$task = xPOST("task") ?: xGET("task");
	$__token = xGET("__token");
	$data = xPOST("data");

	// Attempt to decode 'data' from JSON only if it's a string that's not empty.
	if (!empty($data) && !is_array($data)) {
		$data = json_decode($data, true);
		// If json decoding fails and results in an error, revert to the original data.
		if (json_last_error() !== JSON_ERROR_NONE) {
			$data = xPOST("data");
		}
	}

	// If 'data' was successfully decoded and contains 'task', update task value.
	if (is_array($data) && isset($data['task'])) {
		$task = $data['task'];
	}

	// Assemble the return array with possibly updated 'task' and '__token', and processed 'data'.
	return [
		'task' => $task,
		'__token' => $__token,
		'data' => $data ?: '', // Ensure 'data' is not null when unset
	];
}

/**
 * Safely retrieves a variable from the GET request, optionally performs type
 * checking and secures the input based on the specified type and length.
 *
 * @param string $variableName The name of the variable to retrieve from GET request.
 * @param string $dataType Optional. The type of data expected (for secure processing).
 * @param int $maxLength Optional. Maximum length of the data to accept.
 * @return mixed The sanitized/validated data from the GET request, or an empty string if the variable is not set.
 */
function xGET($variableName, $dataType = "", $maxLength = 50)
{
	// Check if the requested variable is available in query.
	if (isset($_GET[$variableName])) {
		// If data type validation is requested, perform it.
		if ($dataType != '') {
			secure($_GET[$variableName], $variableName, $dataType, $maxLength);
		}
		return $_GET[$variableName];
	} else {
		return "";
	}
}

/**
 * Safely retrieves a variable from the POST request, optionally performs type
 * checking, and secures the input based on the specified type and length.
 *
 * @param string $variableName The name of the variable to retrieve from POST request.
 * @param string $dataType Optional. The type of data expected (for secure processing).
 * @param int $maxLength Optional. Maximum length of the data to accept.
 * @return mixed The sanitized/validated data from the POST request, or an empty string if the variable is not set.
 */
function xPOST($variableName, $dataType = "", $maxLength = 50)
{
	// Check if the requested variable is available in the POST payload.
	if (isset($_POST[$variableName])) {
		// If data type validation is requested, perform it.
		if ($dataType != '') {
			secure($_POST[$variableName], $variableName, $dataType, $maxLength);
		}
		return $_POST[$variableName];
	} else {
		return "";
	}
}

/**
 * Validates and sanitizes the provided string based on the specified type and maximum length. 
 * It throws a custom error response if the validation fails.
 * 
 * @param mixed $str The input string to validate and sanitize.
 * @param string $variableName The name of the variable being sanitized (used in error messages).
 * @param string $dataType The expected data type of the input ('string', 'integer').
 * @param int $maxLength The maximum length allowed for the input.
 * @return void The function operates by effect (errors out or processes inline).
 */
function secure($str, $variableName, $dataType = "string", $maxLength = 50)
{
	// Check if the data exceeds the maximum allowed length.
	if (strlen($str) > $maxLength) {
		RE("($variableName) exceeds the allowed character count of ($maxLength)!", 406);
	}

	// Validate numeric data.
	if ($dataType == 'integer') {
		if (!is_numeric($str)) {
			$actualType = gettype($str);
			RE("$variableName data type ($actualType) does not match expected type ($dataType)!", 406);
		}
		$str = (int) $str;  // Cast to integer after validation.
	} elseif ($dataType != gettype($str)) {  // Check the type for non-integer requirements.
		$actualType = gettype($str);
		RE("$variableName data type ($actualType) does not match expected type ($dataType)!", 406);
	}
}

/**
 * Recursively creates the directory path specified if it does not already exist.
 * 
 * @param string $path The directory path to be created.
 * @return void
 */
function makeFolder($path)
{
	if (!is_dir($path)) {
		mkdir($path, 0777, true);
	}
}

/**
 * Reads and returns the content from a cache file if it's not expired.
 * If the content is valid JSON, it's returned as an array; otherwise, the raw content is returned.
 * Returns false if the cache file is expired or not found.
 *
 * @param string $cache_file Path to the cache file.
 * @param string $timeout Time interval for cache expiration, defaults to immediate expiration ("0 seconds").
 * @return mixed false if expired or not found, decoded array if JSON, or raw content.
 */
function read_cache_file($cache_file, $timeout = "0 seconds")
{
	global $startTime, $lang;

	// Validate cache file parameter
	if (empty($cache_file)) {
		$json = [
			'status' => "error",
			'text' => str_replace('[ACTION]', 'cache_file', $lang['warnings']['actionNotDefined']),
			'timer' => number_format((microtime(true) - $startTime), 4),
		];
		RE(json_encode($json));
		die();
	}

	// Check if cache file exists and hasn't expired
	if (!file_exists($cache_file) || filemtime($cache_file) <= strtotime($timeout)) {
		return false;
	}

	// Retrieve and validate the cache content
	$content = file_get_contents($cache_file);
	$decodedContent = json_decode($content, true);
	if (json_last_error() === JSON_ERROR_NONE) {
		return $decodedContent;
	}

	// Return raw content if not valid JSON
	return $content;
}

/**
 * Handles SQL error logging and response. Generates a detailed error log file, and optionally
 * displays a custom error message depending on the caller's IP address.
 *
 * @param array $options Configuration options for error handling behavior. 
 *                       Includes 'error_show' to control error display.
 */
function SQL_error($options = ['error_show' => true])
{
	http_response_code(500);
	global $startTime;

	// Defined list of IP addresses considered for detailed error display.
	$authorizedIPs = [
		'127.0.0.1',
		"::1",
		"162.55.184.235",
		"95.70.238.217",
	];

	$filenameDelimiter = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ? '\\' : '/';
	$filenameArr = explode($filenameDelimiter, $_SERVER['err']['FILE']);
	$filename = end($filenameArr);
	$line = $_SERVER['err']['LINE'];

	// Ensure backtrace information for debugging context.
	$_SERVER['err']['BACKTRACE'] = debug_backtrace();
	$errorLogPath = "temp/errors/$filename-$line.err";

	if (!is_dir("temp/errors")) {
		makeFolder("temp/errors");
	}

	$contentFile = fopen($errorLogPath, "w") or die("Unable to open file!");
	$errorDetails = "";

	foreach ($_SERVER['err'] as $key => $value) {
		$errorDetails .= is_array($value) ? "$key : " . json_encode($value, JSON_PRETTY_PRINT) . "\n\n" : "$key : $value\n\n";
	}

	fwrite($contentFile, $errorDetails);
	fclose($contentFile);

	$lang['warnings']['errorOnSQL'] = "Error on S-1 <a href='[LINK]' target='_blank'>click here</a> to download error log.";
	$response = [
		'status' => "error",
		'text' => "Error S-1",
		'timer' => number_format((microtime(true) - $startTime), 4)
	];

	if (in_array($_SERVER['REMOTE_ADDR'], $authorizedIPs)) {
		$response['text'] = str_replace('[LINK]', getFullUrl() . "/$errorLogPath", $lang['warnings']['errorOnSQL']);
	}

	// Debugging and error display handling
	if ($options['error_show'] && (!isset($_SESSION['error']) || isset($_SERVER['err']))) {
		$_SESSION['error'] = debug_backtrace();
		dd($response);
		unset($_SESSION['error']);
	}
}

/**
 * if user ips are developer user then return true
 * 
 * @return bool
 */
function isDevopUser()
{
	global $settings;
	if (!isset($settings['debugAuthorizedIPs']))
		$settings['debugAuthorizedIPs'] = array('127.0.0.1', '::1', '49.12.168.126');
	return (in_array($_SERVER['REMOTE_ADDR'], $settings['debugAuthorizedIPs']));
}

/**
 * Enhances debugging information for the given array, including memory usage and execution time.
 * Outputs data differently based on the request method. For GET requests, it displays a more graphical
 * tree-like structure of the array.
 *
 * @param mixed $arr The data to debug. Expected to be an array for detailed debugging info.
 */
function dd($arr)
{
	global $startTime, $root;

	if (!is_array($arr)) {
		RE($arr); // If not an array, output directly.
	}
	// Add debugging info if input is an array

	// Adding memory usage info
	$arr['memory'] = formatBytes(memory_get_usage());

	// Calculation for timer information
	if (isset($root['timerstart'])) {
		$currentTime = microtime(true);
		$arr['timerstart'] = $root['timerstart'];
		$arr['timerdif'] = number_format(($currentTime - $startTime) - $root['timerstart'], 4);
	}

	$arr['timerend'] = number_format((microtime(true) - $startTime), 4);

	if (isset($arr['link']['link_header_script'])) {
		$arr['link']['link_header_script'] = "";
	}
	if (isDevopUser())
		$arr['orgin'] = (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

	ksort($arr);

	// Encode the array to JSON with options to handle special characters and errors
	$json = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

	if (!$_SERVER['REQUEST_METHOD'] == 'GET') {
		RE($json); // For non-GET requests, simply output the JSON.
	}

	// Special handling for GET requests
	echo '<script src="/assets_global/plugins/jquery/v3.7.1/jquery.min.js"></script>
                  <script src="/assets_global/plugins/jquery-json-view/v1.0.0/json-view.min.js"></script>
                  <link href="/assets_global/plugins/jquery-json-view/v1.0.0/json-view.min.css" rel="stylesheet">';
	echo '   <div id="element"></div>
                    <script>
                        $(function() {
                            $("#element").jsonView(' . $json . ');
                            $(".collapser").trigger("click");
                        });
                    </script>';
	RE("");
}

/**
 * Handles HTTP response setup, optional 'temp' update, and outputs provided element before exiting script.
 *
 * @param mixed $element The element to output. If an array is provided, it will be print_r'ed.
 * @param int $httpResponseCode The HTTP response code to set for the response.
 */
function RE($element, $httpResponseCode = 200)
{
	global $db, $now, $root;

	if (!headers_sent() && $httpResponseCode == 200) {
		header('Content-Type: text/html; charset=utf-8', true, 200);
	}

	// Ensure correct HTTP status code is set in header and response
	switch ($httpResponseCode) {
		case 401:
			header("HTTP/1.1 401 Unauthorized", true, 401);
			break;
		case 403:
			header("HTTP/1.1 403 Forbidden", true, 403);
			break;
		case 404:
			header("HTTP/1.1 404 Not Found", true, 404);
			break;
		case 406:
			header("HTTP/1.1 406 Not Acceptable", true, 406);
			break;
		case 500:
			header("HTTP/1.1 500 Internal Server Error", true, 500);
			break;
	}

	// Sets the actual HTTP response code
	if (!headers_sent()) {
		http_response_code($httpResponseCode);
	}

	// Update temp_last_activity if needed and 'temp' is set.
	if (!empty($root['temp']) && $root['temp']['updated_at'] != $now) {
		$temp_id = $root['temp']['temp_id'];

		$item = $root['temp'];
		$item['table'] = 'usr_temp';
		$item['where'][] = "temp_id = '$temp_id'";
		$db->query_update($item);

		if (isset($root['user']['db_connection']) && $root['user']['db_connection'] && $root['user']['db_connection'] != 'default') {
			$item['db_conn_name'] = "default";
			$db->query_update($item);
		}
	}

	if (is_array($element)) {

		// Output $element, formatted if it's an array.
		$json = json_encode($element, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
		echo $json;
	} else {
		echo $element;
	}

	// Clean up session messages if set.
	if (isset($_SESSION['clearmessage'])) {
		unset($_SESSION['messages'], $_SESSION['clearmessage']);
	}

	$db->connection_close();
	exit();
}

/**
 * Converts bytes into a human-readable format.
 *
 * @param int $bytes The number of bytes.
 * @param int $precision The number of decimal places to round to.
 * @return string The formatted size with units.
 */
function formatBytes($bytes, $precision = 2)
{
	$units = ['B', 'KB', 'MB', 'GB', 'TB'];

	if ($bytes <= 0) {
		return '0 B';
	}

	$power = floor(log($bytes, 1024));
	$power = max(min($power, count($units) - 1), 0);

	$bytes /= pow(1024, $power);

	return round($bytes, $precision) . ' ' . $units[$power];
}

/**
 * Retrieves the client's IP address considering common headers used by proxies.
 *
 * @return string The IP address of the client.
 */
function GetIP()
{
	$headers = [
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'REMOTE_ADDR'
	];

	foreach ($headers as $header) {
		$ip = getenv($header);
		if ($ip) {
			// If this header contains a comma (e.g., multiple IPs in X-Forwarded-For), take the first one
			if (strpos($ip, ',') !== false) {
				$ip = trim(explode(',', $ip)[0]);
			}
			return $ip;
		}
	}

	// Fallback IP address if none of the headers are set
	return '0.0.0.0';
}

/**
 * **Warning: Deprecated and Unsafe**
 * "Sanitizes" a string for SQL usage by escaping quotes.
 * This approach does not ensure security against SQL injection attacks.
 * Use prepared statements (with PDO or MySQLi) instead for database operations.
 *
 * @param mixed $str The input string to sanitize.
 * @return mixed The sanitized string, or the original input if it's an array or empty.
 */
function SQL_ready($str)
{
	// Returns the input as is if it's an empty string or an array.
	if ($str === '' || is_array($str)) {
		return $str;
	}

	// Trim whitespace from the beginning and end of the string.
	$str = trim($str);

	// Replace quotes and escape characters with their respective HTML entities.
	$search = ['\\', '"', "'"];
	$replace = ['\\\\', '\"', "\'"];
	$str = str_replace($search, $replace, $str); // Case sensitive replacement is more appropriate here.

	return $str;
}

/**
 * **Warning: Deprecated and Unsafe**
 * Converts arrays or objects to a JSON string and escapes characters for SQL insertion.
 * 
 * Warning: This method is not a substitute for prepared statements or proper validation and
 * escaping mechanisms provided by database access libraries.
 *
 * @param mixed $data Data to be cleared for SQL usage.
 * @return string Sanitized data as a JSON string or original string with escaped characters.
 */
function clear_object_for_sql($data)
{
	// If the input is an array or object, convert it to a JSON string.
	if (is_array($data) || is_object($data)) {
		$data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}

	// Ensure the data is a string to avoid type-related errors
	if (!is_string($data)) {
		return '';
	}

	// Replace specific sequences of characters with their escaped counterparts
	$search = ["'", "\\'", '\"', "\\n"];
	$replace = ["\\'", "'", '\\"', " "];
	$data = str_replace($search, $replace, $data);

	return trim($data);
}

/**
 * Replaces predefined placeholders within a string with their corresponding dynamic values.
 *
 * @param string $str The input string containing placeholders to be replaced.
 * @return string The processed string with placeholders replaced by dynamic values.
 */
function replaceConstants($str)
{
	global $root; // Accessing the global variable

	// dd($root);
	// Define placeholders and their replacement values in an associative array.
	$replacements = [
		"[YEAR]" => date('Y')
	];

	// Iterate over the replacements array and replace each placeholder with its corresponding value.
	if (gettype($str) == "string")
		foreach ($replacements as $placeholder => $value) {
			if (!is_null($value)) {
				$str = str_replace($placeholder, $value, $str);
			}
		}

	return $str;
}

/**
 * Sanitizes a given string by converting special characters to HTML entities.
 *
 * This function replaces double quotes, less than (<), and single quotes/apostrophes with their
 * corresponding HTML entities, to prevent potential HTML injection issues.
 *
 * @param string $str The string to be sanitized.
 * @return string Returns the sanitized string.
 */
function clear_data($str)
{
	$search = ['"', '<', "'"];
	$replace = ['&quot;', '&lt;', '&apos;'];

	return str_replace($search, $replace, $str);
}

/**
 * Renders the layout content of the given item.
 *
 * This function includes a "layout.php" file from the specified path of the item.
 * If the layout file does not exist, it triggers an error handler.
 *
 * @param array $item An associative array with 'path' key indicating the location of the layout file.
 * @return string The output buffer content of the included layout file.
 */
function layoutContent($item)
{
	if (!isset($item['path']) || !file_exists($item['path'] . "/layout.php")) {
		RE("layout not found");
		return '';
	}

	global $root, $lang, $A1, $settings;

	// Assign the tempdir within the referenced $root array
	$root['tempdir'] = $item['path'];
	ob_start();

	try {
		include_once ($item['path'] . "/layout.php");
		$content = ob_get_clean();
	} catch (Exception $e) {
		ob_end_clean(); // Clean output buffer and end it
		RE("Error while loading layout: " . $e->getMessage());
		return '';
	}

	return $content;
}

/**
 * Renders the content of a specific page from within the item's path.
 *
 * This function includes a file specified in the $item array from a 'pages' directory.
 * If the specified file does not exist, an empty string is returned.
 *
 * @param array $item An associative array with 'path' and 'file' keys.
 * - 'path' is the base directory for item-specific files.
 * - 'file' is the filename of the specific page to be rendered.
 * @return string The content of the page or an empty string if the page cannot be found.
 */
function renderPage($item)
{
	global $root, $lang, $A1, $settings, $shemas;

	$root['tempdir'] = $item['path'];
	$view = $item['path'] . '/pages/' . $item['file']; // Construct the full path to the view file

	if (!file_exists($view)) {
		// If the file doesn't exist, return an empty string
		return "";
	}

	// Start output buffering, include the view file and return the buffer content
	ob_start();
	include_once ($view);
	return ob_get_clean();
}

/**
 * Renders the assets for a given item by including the specified file 
 * from an 'assets' directory within the item's path.
 *
 * The function expects an array specifying the path and file to be rendered.
 * If the file exists, it is included and its content is returned.
 * If the file does not exist, an empty string is returned.
 *
 * @param array $item An associative array with 'path' and 'file' keys.
 * - 'path' is the base directory for the item-specific assets.
 * - 'file' is the filename of the specific asset to be rendered.
 * @return string The content of the asset file or an empty string if the file cannot be found.
 */
function renderAssets($item)
{
	global $root, $lang, $A1, $settings;

	$root['tempdir'] = $item['path'];
	$assetPath = $item['path'] . '/assets/' . $item['file']; // Construct the full path to the asset file

	// Check if the file is an actual file rather than a directory
	if (!is_file($assetPath)) {
		return "";
	}

	// Start output buffering, include the asset file, and return the buffer content
	ob_start();
	include_once ($assetPath);
	return ob_get_clean();
}

/**
 * Constructs a JavaScript code snippet for initializing a global JavaScript object 'A1'
 * with key-value pairs from a global PHP array '$A1'.
 *
 * Each key-value pair is encoded as a JSON object, ensuring Unicode characters are unescaped.
 * If the JavaScript 'A1' object doesn't currently have a property, it will be added.
 * The function returns a string representing the JavaScript code to execute.
 *
 * @return string The JavaScript initialization code for the 'A1' object.
 */
function A1DataPrintOut()
{
	global $A1;

	// Initialize an empty array to hold JavaScript code lines
	$jsLines = [];

	// Check if the global variable is an array and iterate through it
	if (is_array($A1)) {
		foreach ($A1 as $key => $value) {
			// Create a JavaScript line ensuring new keys are added to the A1 object
			$jsLines[] = "if(!A1['" . addslashes($key) . "']) A1['" . addslashes($key) . "'] = " . json_encode($value, JSON_UNESCAPED_UNICODE) . ";";
		}
	}

	// Join all JavaScript lines with a semicolon and return the result as a single string
	return implode(';', $jsLines);
}

/**
 * Renders the complete page view based on the given item specifications.
 *
 * This function handles combining layout and page content, dynamic assets, and optionally compresses the output based on specific conditions.
 *
 * @param array $item An associative array with the details needed to render the view.
 * @return string The final rendered content of the page view.
 */
function renderView($item)
{
	ini_set("pcre.backtrack_limit", "100000000");

	// Assign default layout value if not set
	if (!isset($item['layout'])) {
		$item['layout'] = true;
	}

	$layoutContent = $item['layout'] ? layoutContent($item) : '{{pagesContent}}';
	$pageContent = renderPage($item);
	$layoutContent = str_replace('{{pagesContent}}', $pageContent, $layoutContent);

	// Replace A1DATA placeholer with actual content
	$layoutContent = preg_replace('@{\s+{\s+A1DATA\s+}\s+}@', A1DataPrintOut(), $layoutContent);

	// Dynamic content replacement
	$dynamicContents = [
		"menu-user-header.php" => "{{usersMenu}}",
		"widget-vehicle-picker.php" => "{{widget-vehicle-picker}}",
	];

	foreach ($dynamicContents as $filename => $placeholder) {
		$item['file'] = $filename;
		$layoutContent = str_replace($placeholder, renderAssets($item), $layoutContent);
	}

	// Compressing the content if conditions are met
	$isLocalMachine = $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === "::1";
	$forcedMerge = strpos($_SERVER['REQUEST_URI'], 'MERGE') !== false;
	$forcedUnmerge = strpos($_SERVER['REQUEST_URI'], 'UNMERGE') !== false;

	$shouldCompress = !$isLocalMachine || $forcedMerge;
	if ($forcedUnmerge) {
		$shouldCompress = false;
	}

	if ($shouldCompress) {

		$searchPatterns = [
			'@>\s+(.*)\s+<@' => '>$1<',  // Remove spaces between tags
			'@>\s+<@' => '><',    // Remove spaces between tags
			'/<!--(.*)-->/Uis' => '',      // Remove HTML comments
		];

		foreach ($searchPatterns as $pattern => $replacement) {
			$layoutContent = preg_replace($pattern, $replacement, $layoutContent);
		}
	}

	return $layoutContent;
}

/**
 * Clears cache files and directories based on given parameters or query strings.
 * Can also handle remote dummy file creation for cache busting.
 *
 * @param array $obj An associative array that can contain 'task', 'folders', and 'prefix_file' to customize cache clearing.
 */
function clear_cache($obj = array())
{
	$temp = "temp";
	$domain = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];
	$query = $_GET['query'] ?? '';

	// Default values
	$task = "";
	$cacheFolders = [];
	$cacheRemote = [];
	$prefixFile = "";

	// Override default values if provided in $obj parameter
	if (isset($obj['task'])) {
		$task = $obj['task'];
	}
	if (isset($obj['folders'])) {
		$cacheFolders = $obj['folders'];
	}
	if (isset($obj['prefix_file'])) {
		$prefixFile = $obj['prefix_file'];
	}

	// Determine task based on query or provided task
	if (strpos($query, 'CLEAR') !== false || !empty($task)) {
		if (strpos($query, 'CLEARALL') !== false) {
			$task = "CLEARALL";
			$cacheFolders = [
				"$temp/cache/search",
				"$temp/export",
				"$temp/images",
				"$temp/cache/sql",
				"$temp/cache/html",
				"$temp/json",
				"$temp/sync",
				"$temp/catalogs",
			];
			$cacheRemote = [
				"$temp/dummy.js",
				"$temp/dummy.css",
				"$temp/dummy.gif",
			];
		}

		// Clear specified cache directories
		foreach ($cacheFolders as $dir) {
			if (is_dir($dir)) {
				@array_map("unlink", glob("$dir/{$prefixFile}*.*"));
			} else {
				makeFolder($dir);
			}
		}

		// Process remote dummy files for cache busting
		$dummyContentMap = [
			"$temp/dummy.gif" => "R0lGODlhAQABAAAAACwAAAAAAQABAAA=",
			"$temp/dummy.css" => "/* dummy CSS */",
			"$temp/dummy.js" => "/* dummy JS */",
		];

		foreach ($cacheRemote as $file) {
			if (!is_link($file)) {
				$content = $dummyContentMap[$file] ?? '';
				file_put_contents($file, $content) or die("Unable to write to file $file");
			}
			get_web_page("$domain/$file?$task");
		}
	}
}

/**
 * Attempts to JSON-decode each value of the given array. If the decoding fails,
 * the function keeps the original value.
 *
 * @param array $row The associative array containing the data to be decoded.
 * @return array The array with its values JSON-decoded where possible.
 */
function serialize_data($row)
{
	foreach ($row as $key => $value) {
		// Attempt to decode the JSON data
		$decodedValue = json_decode($value, true);

		// Check for JSON errors and fall back to original value if necessary
		if (json_last_error() === JSON_ERROR_NONE) {
			// If there's no error, update the value to the decoded JSON
			$row[$key] = $decodedValue;
		}
		// If there's a JSON error, we leave $row[$key] with its original value
	}
	return $row;
}

/**
 * Retrieves a web page using cURL with optional custom cURL options.
 *
 * @param string $url The URL to fetch.
 * @param array|false $curl_opts Optional associative array of cURL options to override defaults.
 * @return array Contains the content, error number, error message, header, and cURL information.
 */
function get_web_page($url, $curl_opts = false)
{
	// Default user agent string
	$userAgent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

	// Set up cURL options with some default values
	$options = [
		CURLOPT_URL => trim($url),
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_USERAGENT => $userAgent,
		CURLOPT_COOKIEFILE => __DIR__ . "/cookie.txt", // Fixed slashes for file path
		CURLOPT_COOKIEJAR => __DIR__ . "/cookie.txt",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_AUTOREFERER => true,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_HTTPHEADER => [
			'Proxy-Connection: Close',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Language: en-US,en;q=0.5',
			'Connection: keep-alive',
			'Upgrade-Insecure-Requests: 1',
			'Cache-Control: max-age=0',
		],
	];

	// Override any provided cURL options
	if (is_array($curl_opts)) {
		foreach ($curl_opts as $key => $value) {
			$options[$key] = $value;
		}
	}

	// Initialize cURL session
	$ch = curl_init();
	curl_setopt_array($ch, $options);

	// Execute cURL session and store results
	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);
	$header = curl_getinfo($ch);
	curl_close($ch);

	// Prepare return structure
	$item = [
		'content' => $content,
		'errno' => $err,
		'errmsg' => $errmsg,
		'header' => $header,
	];

	return $item;
}

/**
 * Creates a random string of lowercase alphanumeric chars.
 * 
 * @param int $length The length of the generated random string
 * 
 * @return string the generated string
 */
function randomStr($length = 10)
{
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
}

/**
 * Generates a hashed token for session-based security checks.
 *
 * @param string|false $token An optional token to use for hashing, defaults to session ID if not provided.
 * @return string The generated session token.
 */
function __token($token = false)
{
	global $settings;

	// Use either the provided token or fallback to session ID
	$token = $token ?: (isset($_SESSION['id']) ? $_SESSION['id'] : session_id());

	// Generate a hash of the token with the global salt
	$_SESSION['__token'] = md5($token . $settings['site']['salt']);

	return $_SESSION['__token'];
}

/**
 * Logs the user out by terminating the current session and clearing the user cookie.
 */
function logOut()
{
	if (isset($_SESSION["id"])) {
		// Clear the session data and destroy it
		session_unset();    // Unset $_SESSION variable for the run-time
		session_regenerate_id(true);
		session_destroy();  // Destroy session data in storage

		// Clear the 'user' cookie
		setcookie("user", "", time() - (60 * 60 * 24 * 30), "/");

		// Perform a redirection after logout
		Redirect('/', true);
	}
}

/**
 * Redirects to a specified URL. If the request was initiated by AJAX with a special "noneajax" header,
 * the redirect is not performed.
 *
 * @param string $url The URL to redirect to.
 * @param bool $permanent Whether to send a permanent (301) or temporary (302) redirect status code.
 */
function Redirect($url, $permanent = false)
{
	// Check for a special AJAX request header
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === "noneajax") {

		// Only perform the redirect if headers have not already been sent
		if (!headers_sent()) {
			header('Location: ' . $url, true, $permanent ? 301 : 302);
		}

		// Terminate the execution after initiating a redirect
		exit();
	}
}

/**
 * Scans a given path for files and folders and returns their metadata in an array structure.
 *
 * @param string $path The relative path within the 'upload' directory to scan.
 * @return array An array representing the files and folders, each with its metadata.
 */
function filesAndfolders($path)
{
	global $settings;
	$root = "upload" . $path;
	$selectedfiles = xPOST("files");
	if ($selectedfiles == '') {
		$selectedfiles = " ";
	}
	$json = array();
	if (file_exists($root)) {
		$files = scandir($root);
		natcasesort($files);
		if (count($files) > 2) {
			$iLOOP = 0;
			foreach ($files as $file) {
				if (file_exists($root . $file) && $file != '.' && $file != '..' && is_dir($root . $file)) {
					$json[$iLOOP]['text'] = htmlentities($file);
					$json[$iLOOP]['id'] = htmlentities(str_ireplace('//', '/', $path . '/' . $file . '/'));
					if (htmlentities($file) != '') {
						$strpos = strpos($selectedfiles, htmlentities($file));
					} else {
						$strpos = false;
					}
					if ($strpos !== false) {
						$json[$iLOOP]['state']['opened'] = false;
						$json[$iLOOP]['state']['disabled'] = false;
					} else {
						$json[$iLOOP]['state']['opened'] = false;
						$json[$iLOOP]['state']['disabled'] = false;
					}

					$json[$iLOOP]['children'] = true;
					$json[$iLOOP]['icon'] = 'folder';
					$iLOOP++;
				}
			}
			$xLOOP = $iLOOP;
			foreach ($files as $file) {
				$ext = strtolower(preg_replace('/^.*\./', '', $file));
				if (file_exists($root . $file) && $ext != 'json' && $file != '.' && $file != '..' && !is_dir($root . $file)) {
					$json[$xLOOP]['text'] = htmlentities($file);
					$json[$xLOOP]['id'] = htmlentities(str_ireplace('//', '/', $path . '/' . $file));
					$json[$xLOOP]['children'] = false;
					$json[$xLOOP]['mtime'] = date('F d Y h:i A', filemtime($root . $file));
					$json[$xLOOP]['icon'] = "file file-$ext";
					if (strpos($selectedfiles, $file) !== false) {
						$json[$xLOOP]['state']['selected'] = true;
						$json[$xLOOP]['state']['undetermined'] = true;
					} else {
						$json[$xLOOP]['state']['selected'] = false;
					}
					$jsonfile = $root . $file . '.json';
					if (file_exists($jsonfile)) {
						$tits = json_decode(file_get_contents($jsonfile), true);
						$tits['file'] = "/" . $root . $file;
						$json[$xLOOP]['li_attr']['data-json'] = json_encode($tits);
						$json[$xLOOP]['li_attr']['data-mtime'] = date('F d Y h:i A', filemtime($root . $file));
						$json[$xLOOP]['li_attr']['data-icon'] = "file-$ext";
					} else {
						$tits["file"] = "/" . $root . $file;
						for ($x = 0; $x < count($settings['languages']); $x++) {
							$tits["alt"][$settings['languages'][$x]['short']] = "";
							$tits["title"][$settings['languages'][$x]['short']] = "";
						}
						$json[$xLOOP]['li_attr']['data-json'] = json_encode($tits);
						$json[$xLOOP]['li_attr']['data-mtime'] = date('F d Y h:i A', filemtime($root . $file));
						$json[$xLOOP]['li_attr']['data-icon'] = "file-$ext";
					}
					$xLOOP++;
				}
			}
		}
	}
	return $json;
}

function loadSVG($file)
{
	$file = ltrim($file, '/');
	if (is_file($file)) {
		return file_get_contents($file);
	} else {
		return "<h1>$file not found</h1>";
	}
}

function isValidDate($date, $format = 'Y-m-d')
{
	return date($format, strtotime($date)) === $date;
}

function load_template($fileslug = "ORDER", $file = false)
{
	$fileslug = str_replace(' ', '-', $fileslug);
	if (!$file)
		$file = "settings/email-temp/$fileslug.html";
	$content = "";
	if (file_exists($file)) {
		$content = file_get_contents($file);
	}
	return $content;
}

/* ---------- START OF DATA VALIDATION LOGIC ---------- */

/**
 * Validates if specified fields in a data array exist and meet defined criteria. Supports deep checks with path-like syntax, type validation, truthiness checks, and "either-or" logic for multiple fields.
 * 
 * The function allows specifying required fields with additional constraints:
 * - Path-like syntax for nested data checking (e.g., 'user/details/name').
 * - Type specification with optional length constraint (e.g., '(str@50)name' for a string up to 50 characters).
 * - Truthiness checks by prefixing the field with an asterisk (e.g., '*isActive' ensures the field is truthy).
 * - "Either-or" logic by passing a nested array of fields, where at least one must be valid (e.g., ['email', '*phone'] requires either 'email' to exist or 'phone' to be truthy).
 *
 * Supported types:
 * - str: String type, with an optional length (e.g., '(str@10)field').
 * - int: Integer type, with an optional length representing the maximum number of digits (e.g., '(int@5)field').
 * - bool: Boolean type, no length constraint applicable (e.g., '(bool)field'), string 'true' or 'false' will also work.
 * - array: Array type, no length constraint applicable (e.g., '(array)field').
 *
 * @param array $requiredFields An array of strings representing the fields to check, with optional type, length, and truthiness specifications. Nested arrays implement "either-or" logic.
 * @param array $data The associative array of data to validate against the specified fields and constraints.
 * 
 * @return void The function directly outputs a JSON-encoded error message and halts execution if validation fails. No return value on successful validation.
 * 
 * ---
 * 
 * #Usage:
 * Here is how you can use the checkRequiredFields function:
 * 
 * 
 * ```php
 * checkRequiredFields(
 *     [
 *         'name', // Checks if 'name' exists.
 *         '(str@50)contact/email', // Checks if 'contact/email' exists, is a string, and is no longer than 50 characters.
 *         '(int)age', // Checks if 'age' exists and is an integer.
 *         '*isActive', // Checks if 'isActive' exists and is truthy.
 *         ['(str)contact/email', '(str)*contact/phone'], // Checks if either 'contact/email' exists as a string or 'contact/phone' exists and is a truthy string.
 *         '(bool)*isVerified', // Checks if 'isVerified' exists, is a boolean, and is truthy.
 *     ],
 *     $data
 * );
 * ```
 * 
 */
function checkRequiredFields($data, $requiredFields = array(), $debug = false)
{
	global $lang;
	$missingFields = [];

	foreach ($requiredFields as $field) {
		if (is_array($field)) {
			// Process nested array with either-or logic
			$eitherExists = false;
			foreach ($field as $subField) {
				if (processField($subField, $data, $debug)) {
					$eitherExists = true;
					break;
				}
			}
			if (!$eitherExists) {
				$missingFields[] = implode(' or ', $field); // None of the fields in the either-or logic are valid
			}
		} else {
			if (!processField($field, $data, $debug)) {
				$missingFields[] = $field; // Field does not exist or is not valid
			}
		}
	}

	// Combine missing and invalid fields for error reporting
	if (!empty($missingFields)) {
		$json = array();
		$json['status'] = "error";
		$json['text'] = $lang['warnings']['FieldsAreRequiredOrInvalid'] . implode(', ', $missingFields);
		dd($json);
	}
}

function processField($field, $data, $debug)
{
	$checkForTruthyValue = strpos($field, '*');
	if ($checkForTruthyValue !== false) {
		$field = substr_replace($field, '', $checkForTruthyValue, 1); // Remove the asterisk
	}

	// Parse field for type and length constraints
	preg_match('/\((.*?)\)(.*)/', $field, $matches);
	$typeAndLength = $matches[1] ?? '';
	$fieldPath = $matches[2] ?? $field;
	$type = explode('@', $typeAndLength)[0];
	$length = explode('@', $typeAndLength)[1] ?? null;

	// Split the path by '/' and traverse the data array
	$path = explode('/', $fieldPath);
	$valueFound = $data;
	foreach ($path as $part) {
		if (array_key_exists($part, $valueFound)) {
			$valueFound = $valueFound[$part];
		} else {
			return false; // Part of the path not found
		}
	}

	// Perform type and length validation
	if ($checkForTruthyValue !== false && (empty($valueFound) && !is_numeric($valueFound))) { //---the value 0 or "0" is still considered valid values by this logic
		return false; // Value is falsy
	} elseif (!validateField($valueFound, $type, $length)) {
		return false; // Value is invalid according to type/length
	}

	return true; // Field exists and is valid
}

function validateField($value, $type, $length)
{
	switch ($type) {
		case 'str':
			if (!is_string($value))
				return false;
			if ($length !== null && mb_strlen($value) > $length)
				return false;
			break;
		case 'int':
			if (!is_int($value) && !(is_numeric($value) && (int) $value == $value))
				return false;
			if ($length !== null && strlen((string) $value) > $length)
				return false;
			break;
		case 'bool':
			if (!(is_bool($value) || $value === 'true' || $value === 'false'))
				return false;
			break;
		case 'array':
			if (!(is_array($value)))
				return false;
			break;
	}
	return true;
}

// ---------- END OF DATA VALIDATION LOGIC ---------- //

/**
 * Check if a string contains a given substring.
 * 
 * @param string $haystack The string to search in.
 * @param string $needle The substring to search for.
 * @return bool Returns true if the needle exists in the haystack, false otherwise.
 */
function string_contains($haystack, $needle)
{
	return strpos($haystack, $needle) !== false;
}
