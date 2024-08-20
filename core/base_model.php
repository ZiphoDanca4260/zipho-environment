<?php

class BaseModel
{
    // --- INSTANCE

    private static $instance = null; //---singleton instance

    private $conn_list; //---open connection list

    private $conn_name; //---current operations connection name

    // --- CONSTRUCT VARIABLES

    private $conf; //---db configuration json file

    private $now; //---script start time

    private $cache_timeout; //---cache timeout setting, comes from `global $settings`

    private $debug_authorized_IPS; //---comes from settings.json, adds debug_backtrace to errors.

    // --- CUSTOM PROPERTIES
    // --- FLAGS

    private $break_on_error; //---dump&die in case of error

    private $cache_active; //---try to read query result from cache file, or write to cache file after reading

    private $debug_SQL = false; //---print the SQL query without running it


    // --- INITIALIZER -----------------------------------------------------------------------------


    private function __construct($construct)
    {
        if (!isset($construct['config_path'])) {
            throw new Exception('Database configuration file path is missing!');
        }

        if (!file_exists($construct['config_path'])) {
            throw new Exception('Database configuration file could not be found!');
        }

        if (!$this->conf = file_get_contents($construct['config_path'])) {
            throw new Exception('Database configuration file could not be fetched!');
        }

        if (!$this->conf = json_decode($this->conf, true)) {
            throw new Exception('Database configuration file could not be decoded!');
        }

        $this->now = $construct['now'] ?? date('Y-m-d H:i:s');

        $this->cache_timeout = $construct['cache_timeout'] ?? "0 seconds";

        $this->debug_authorized_IPS = $construct['debug_authorized_IPS'] ?? array();

        makeFolder('temp/cache/sql/');
    }

    public static function instance_get($construct)
    {
        if (self::$instance == null) {
            self::$instance = new self($construct);
        }

        return self::$instance;
    }


    // --- CRUD OPERATIONS -------------------------------------------------------------------------


    public function query_create($item)
    {
        $this->conn_name = isset($item['db_conn_name']) ? $this->connection_open($item['db_conn_name']) : $this->connection_open();

        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;
        $table = $item['table'];

        unset($item['table'], $item['db_conn_name'], $item['ddSQLError']);

        //---input santization
        foreach ($item as $key => $value) {
            $value = $this->sanitize_value($value);
            if (isset($value['status']) && $value['status'] == 'error') {
                return $value;
            }

            $item[$key] = $value;
        }

        if (!isset($item['created_at']) || empty($item['created_at'])) {
            $item['created_at'] = $this->now;
        }

        $keys = implode('`,`', array_keys($item));
        $values = array_map(function ($value) {
            return is_null($value) ? 'NULL' : "'" . $value . "'";
        }, array_values($item));
        $values = implode(",", $values);

        $SQL = "INSERT INTO `$table` (`$keys`) VALUES ($values) ;";
        if ($this->debug_SQL) {
            RE($SQL);
        }

        $result = $this->conn_list[$this->conn_name]->query($SQL);
        if (!$result) {
            return $this->query_error($SQL);
        }

        $json = array();
        $json['status'] = "success";
        $json['id'] = $this->conn_list[$this->conn_name]->insert_id;
        return $json;
    }

    public function query_read($item)
    {
        $this->conn_name = isset($item['db_conn_name']) ? $this->connection_open($item['db_conn_name']) : $this->connection_open();

        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->cache_active = $item['cache'] ?? false;
        $this->debug_SQL = $item['debugSQL'] ?? false;

        //---build select
        if (!isset($item['selects']) || !is_array($item['selects'])) {
            $item['selects'] = array("{$item['table']}.*");
        }

        $item['join'] = isset($item['join']) ? $item['join'] : array();
        $item['limit'] = isset($item['limit']) ? $item['limit'] : 'LIMIT 0,10';
        $item['resultby'] = isset($item['resultby']) ? $item['resultby'] : 'array';

        $SELECT = join(",\n", $item['selects']);
        $TABLE = $item['table'];
        $JOIN = join(" \r\n ", $item['join']);
        $WHERE = isset($item['where']) && !empty($item['where']) ? 'WHERE ' . join(" AND\n", $item['where']) : '';
        $ORDERBY = isset($item['order']) && !empty($item['order']) ? 'ORDER BY ' . join(",\n", $item['order']) : '';
        $LIMIT = $item['limit'];

        $SQL = "SELECT 
                    $SELECT
                FROM 
                    `$TABLE` 
                    $JOIN
                $WHERE
                $ORDERBY
                $LIMIT;";

        //---if escapes are set then apply
        if (!isset($item['escapes'])) {
            $item['escapes'] = array();
        }

        if (!is_array($item['escapes'])) {
            $item['escapes'] = array();
        }

        foreach ($item['escapes'] as $value) {
            $escaped = mysqli_real_escape_string($this->conn_list[$this->conn_name], $value);
            $SQL = str_replace($value, $escaped, $SQL);
        }

        if ($this->debug_SQL) {
            RE($SQL);
        }

        $cache_file = $_SERVER['DOCUMENT_ROOT'] . "/temp/cache/sql/crud-$TABLE-" . md5($SQL) . ".json";
        $json = false;
        if ($this->cache_active) {
            $json = read_cache_file($cache_file, $this->cache_timeout);
        }

        if (!$json) {
            $result = $this->conn_list[$this->conn_name]->query($SQL);

            if (!$result) {
                return $this->query_error($SQL);
            }

            $number = mysqli_num_rows($result);
            $json = array();
            $json['status'] = 'success';
            $json['data'] = array();
            $json['SQL'] = $SQL;

            if ($number) {
                while ($row = mysqli_fetch_assoc($result)) {
                    foreach ($row as $key => $value) {
                        if ($value !== null) {
                            $val = $value;
                            $value = json_decode($val, true, 512, JSON_BIGINT_AS_STRING);

                            if (json_last_error()) {
                                $value = $val;
                            }
                        }

                        $row[$key] = $value;
                    }
                    if ($item['resultby'] == 'array') {
                        $json['data'][] = $row;
                    } else {
                        $json['data'][$row[$item['resultby']]] = $row;
                    }
                }
            }

            if ($this->cache_active) {
                $content_file = fopen($cache_file, "w") or die("Unable to open file!");
                fwrite($content_file, json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING));
                fclose($content_file);
            }
        }

        return $json;
    }

    public function query_update($item)
    {
        $this->conn_name = isset($item['db_conn_name']) ? $this->connection_open($item['db_conn_name']) : $this->connection_open();
        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;

        if (!isset($item['where'])) {
            $json['status'] = "error";
            $json['text'] = "where query not defined";
            return $json;
        }

        $WHERE = join(' AND ', $item['where']);
        $TABLE = $item['table'];

        unset($item['table'], $item['where'], $item['db_conn_name'], $item['ddSQLError'], $item['debugSQL']);

        $item['updated_at'] = $this->now;

        foreach ($item as $key => $value) {
            $value = $this->sanitize_value($value);
            if (isset($value['status']) && $value['status'] == 'error') {
                return $value;
            }

            //---generate the clause with null values properly handled
            if ($value === NULL) {
                $SET[] = "`$key` = NULL";
            } else {
                $SET[] = "`$key` ='" . $value . "'";
            }
        }

        $SET = implode(',', $SET);
        $SQL = "UPDATE `$TABLE` SET $SET WHERE $WHERE;";
        if ($this->debug_SQL) {
            RE($SQL);
        }

        $result = $this->conn_list[$this->conn_name]->query($SQL);
        if (!$result) {
            return $this->query_error($SQL);
        }

        $json = array();
        $json['status'] = "success";
        return $json;
    }

    public function query_delete($item)
    {
        $this->conn_name = isset($item['db_conn_name']) ? $this->connection_open($item['db_conn_name']) : $this->connection_open();
        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;

        if (!isset($item['where'])) {
            $json['status'] = "error";
            $json['text'] = "where query not defined";
            return $json;
        }

        if (!isset($item['selects']) || !is_array($item['selects'])) {
            $item['selects'] = array();
        }

        $SELECT = join(',', $item['selects']);
        $TABLE = $item['table'];
        $WHERE = join(' AND ', $item['where']);
        $SQL = "DELETE 
                    $SELECT
                FROM 
                    $TABLE
                WHERE 
                    $WHERE;";
        if ($this->debug_SQL) {
            RE($SQL);
        }

        $result = $this->conn_list[$this->conn_name]->query($SQL);
        if (!$result) {
            return $this->query_error($SQL);
        }

        $json = array();
        $json['status'] = "success";
        return $json;
    }


    // --- CUSTOM QUERIES --------------------------------------------------------------------------


    public function query_read_schemas($item)
    {
        checkRequiredFields($item, ['(array)*table_names']);

        $this->conn_name = isset($item['conn_name']) ? $this->connection_open($item['conn_name']) : $this->connection_open();
        $database_name = $this->conf['database'][$this->conn_name]['name'];

        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;
        $this->cache_active = $item['cache'] ?? false;

        $schemas = array();
        //---all tables must be queried one by one.
        foreach ($item['table_names'] as $table_name) {

            $SELECT = "A.COLUMN_NAME, A.DATA_TYPE, A.CHARACTER_MAXIMUM_LENGTH, A.NUMERIC_PRECISION, A.NUMERIC_SCALE, A.COLUMN_DEFAULT, A.COLUMN_KEY, A.IS_NULLABLE,
                        CASE WHEN TC.CONSTRAINT_TYPE = 'UNIQUE' THEN 'YES' ELSE 'NO' END AS IS_UNIQUE,
                        TC.CONSTRAINT_NAME AS UNIQUE_KEY_NAME";
            $TABLE = "information_schema.COLUMNS A";
            $JOIN = "LEFT JOIN information_schema.KEY_COLUMN_USAGE AS KCU 
                                ON A.TABLE_SCHEMA = KCU.TABLE_SCHEMA 
                                AND A.TABLE_NAME = KCU.TABLE_NAME 
                                AND A.COLUMN_NAME = KCU.COLUMN_NAME
                    LEFT JOIN information_schema.TABLE_CONSTRAINTS AS TC 
                                ON KCU.TABLE_SCHEMA = TC.TABLE_SCHEMA 
                                AND KCU.TABLE_NAME = TC.TABLE_NAME 
                                AND KCU.CONSTRAINT_NAME = TC.CONSTRAINT_NAME";
            $WHERE = "A.TABLE_NAME IN ('$table_name') AND A.TABLE_SCHEMA IN ('$database_name')";
            $ORDER = "ORDER BY (A.COLUMN_KEY = 'PRI') DESC, A.ORDINAL_POSITION ";
            $LIMIT = "LIMIT 0,100";

            $SQL = "SELECT 
                    $SELECT
                FROM 
                    $TABLE
                $JOIN
                WHERE 
                    $WHERE
                $ORDER
                $LIMIT;";

            $json = false;

            $cache_file = $_SERVER['DOCUMENT_ROOT'] . "/temp/cache/sql/schema-$table_name.json";
            if ($this->cache_active) {
                $json = read_cache_file($cache_file, $this->cache_timeout);
            }

            if (!$json) {
                $result = $this->conn_list[$this->conn_name]->query($SQL);

                if (!$result) {
                    return $this->query_error($SQL);
                }

                $number = mysqli_num_rows($result);
                $json = array();
                $json['data'] = array();
                $json['SQL'] = $SQL;

                if ($number) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['COLUMN_DEFAULT'] == "NULL") {
                            $row['COLUMN_DEFAULT'] = null;
                        } else {
                            $row['COLUMN_DEFAULT'] = str_replace("'", "", $row['COLUMN_DEFAULT']);
                        }
                        $json['data'][] = $row;
                    }
                }

                $schemas[$table_name] = $json;

                if ($this->cache_active) {
                    $content_file = fopen($cache_file, "w") or die("Unable to open file!");
                    fwrite($content_file, json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING));
                    fclose($content_file);
                }
            }
        }

        return $schemas;
    }

    public function query_read_related_child_tables($item)
    {
        checkRequiredFields($item, ['(str)*table_name']);

        $this->conn_name = isset($item['conn_name']) ? $this->connection_open($item['conn_name']) : $this->connection_open();
        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;
        $this->cache_active = $item['cache'] ?? false;

        $database_name = $this->conf['database'][$this->conn_name]['name'];
        $table_name = $item['table_name'];

        $SELECT = "table_name, referenced_column_name, column_name";
        $FROM = "information_schema.key_column_usage";
        $WHERE = "referenced_table_schema IN ('$database_name') AND referenced_table_name IN ('$table_name')";
        $LIMIT = "LIMIT 0,100";

        $SQL = "SELECT 
                    $SELECT
                FROM
                    $FROM
                WHERE
                    $WHERE
                $LIMIT";
        if ($this->debug_SQL) {
            RE($SQL);
        }

        $json = false;

        $cache_file = $_SERVER['DOCUMENT_ROOT'] . "/temp/cache/sql/related_childs-$table_name-$database_name.json";
        if ($this->cache_active) {
            $json = read_cache_file($cache_file, $this->cache_timeout);
        }

        if (!$json) {
            $result = $this->conn_list[$this->conn_name]->query($SQL);

            if (!$result) {
                return $this->query_error($SQL);
            }

            $number = mysqli_num_rows($result);
            $json = array();
            $json['data'] = array();
            $json['SQL'] = $SQL;

            if ($number) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $json['data'][] = $row;
                }
            }

            if ($this->cache_active) {
                $content_file = fopen($cache_file, "w") or die("Unable to open file!");
                fwrite($content_file, json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING));
                fclose($content_file);
            }
        }

        return $json;
    }

    public function query_read_related_parent_tables($item)
    {
        checkRequiredFields($item, ['(str)*table_name']);

        $this->conn_name = isset($item['conn_name']) ? $this->connection_open($item['conn_name']) : $this->connection_open();
        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;
        $this->cache_active = $item['cache'] ?? false;

        $database_name = $this->conf['database'][$this->conn_name]['name'];
        $table_name = $item['table_name'];

        $SELECT = "table_name, referenced_column_name, column_name, referenced_table_name";
        $FROM = "information_schema.key_column_usage";
        $WHERE = "referenced_table_schema IN ('$database_name') AND table_name IN ('$table_name')";
        $LIMIT = "LIMIT 0,100";

        $SQL = "SELECT 
                    $SELECT
                FROM
                    $FROM
                WHERE
                    $WHERE
                $LIMIT";
        if ($this->debug_SQL) {
            RE($SQL);
        }

        $json = false;

        $cache_file = $_SERVER['DOCUMENT_ROOT'] . "/temp/cache/sql/related_parents-$table_name-$database_name.json";
        if ($this->cache_active) {
            $json = read_cache_file($cache_file, $this->cache_timeout);
        }

        if (!$json) {
            $result = $this->conn_list[$this->conn_name]->query($SQL);

            if (!$result) {
                return $this->query_error($SQL);
            }

            $number = mysqli_num_rows($result);
            $json = array();
            $json['data'] = array();
            $json['SQL'] = $SQL;

            if ($number) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $json['data'][] = $row;
                }
            }

            if ($this->cache_active) {
                $content_file = fopen($cache_file, "w") or die("Unable to open file!");
                fwrite($content_file, json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING));
                fclose($content_file);
            }
        }

        return $json;
    }

    public function query_replace_into($item)
    {
        $this->conn_name = isset($item['db_conn_name']) ? $this->connection_open($item['db_conn_name']) : $this->connection_open();
        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;

        $TABLE = $item['table'];
        unset($item['table'], $item['db_conn_name'], $item['ddSQLError'], $item['debugSQL']);

        $item['updated_at'] = $this->now;
        $pairs = array();

        foreach ($item as $key => $value) {
            $value = $this->sanitize_value($value);
            if (isset($value['status']) && $value['status'] == 'error') {
                return $value;
            }

            //---generate the clause with null values properly handled
            if ($value === NULL) {
                $pairs[] = "`$key`=NULL";
            } else {
                $pairs[] = "`$key`='" . $value . "'";
            }
        }

        $pairs_part = implode(', ', $pairs);
        $SQL = "REPLACE INTO `$TABLE` SET $pairs_part;";
        if ($this->debug_SQL) {
            RE($SQL);
        }

        $result = $this->conn_list[$this->conn_name]->query($SQL);
        if (!$result) {
            return $this->query_error($SQL);
        }

        $json = array();
        $json['status'] = "success";
        return $json;
    }

    public function query_change_group_concat_limit($item)
    {
        $this->conn_name = isset($item['db_conn_name']) ? $this->connection_open($item['db_conn_name']) : $this->connection_open();
        $this->break_on_error = $item['ddSQLError'] ?? true;
        $this->debug_SQL = $item['debugSQL'] ?? false;

        try {
            $limit = (int) $item['group_concat_limit'];
        } catch (\Throwable $th) {
            dd(['status' => 'error', 'text' => 'Group concat limit could not be set.']);
        }

        $SQL = "SET SESSION group_concat_max_len = $limit;";
        if ($this->debug_SQL) {
            RE($SQL);
        }
        $result = $this->conn_list[$this->conn_name]->query($SQL);
        if (!$result) {
            return $this->query_error($SQL);
        }
        $json = array();
        $json['status'] = "success";
        $json['result'] = $result;
        return $json;
    }


    // --- UTILITIES -------------------------------------------------------------------------------


    private function connection_open($conn_name = null)
    {
        global $root;

        $conn_name ??= $root['user']['db_connection'] ?? "default";
        if (!$conn_name) {
            $conn_name = 'default';
        }

        if (isset($this->conn_list[$conn_name])) {
            return $conn_name;
        }

        // Check if the connection is already open or the database config is not set/active.
        if (!empty($this->conf['database'][$conn_name]) && $this->conf['database'][$conn_name]['active']) {

            // Tries to establish a new database connection with error control operator suppressed.
            $newConnection = new mysqli(
                $this->conf['database'][$conn_name]['server'],
                $this->conf['database'][$conn_name]['user'],
                $this->conf['database'][$conn_name]['pass'],
                $this->conf['database'][$conn_name]['name']
            );

            // Error handling for connection failure.
            if ($newConnection->connect_errno) {

                // Log error or handle it according to the application's error handling strategy.
                error_log("Database connection error ({$conn_name}): " . $newConnection->connect_error);

                $this->connection_error($newConnection->connect_error);

                die("Unable to establish a database connection.");
            }

            $newConnection->set_charset("utf8");
            $this->conn_list[$conn_name] = $newConnection;
        }

        return $conn_name;
    }

    public function connection_close($conn_name = null)
    {
        if ($conn_name) {
            if (!isset($this->conn_list[$conn_name])) {
                $this->conn_list[$conn_name]->close();
                unset($this->conn_list[$conn_name]);

                return;
            }
        }

        if (!empty($this->conn_list)) {
            foreach ($this->conn_list as $conn_name => $connection_obj) {
                $connection_obj->close();
                unset($this->conn_list[$conn_name]);
            }

            $this->conn_list = null;
        }
    }

    private function sanitize_value($value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if ($value === false) {
                $return = ['status' => 'error', 'text' => 'json_encode() has failed to encode object...'];
                if ($this->break_on_error) {
                    dd($return);
                } else {
                    return $return;
                }
            }
        }

        if ((empty($value) && $value !== '0' && $value !== 0) || strcasecmp($value, 'null') == 0) {
            return NULL;
        } else {
            $value = trim($value);
            return $this->conn_list[$this->conn_name]->real_escape_string($value);
        }
    }


    // --- ERROR HANDLERS --------------------------------------------------------------------------


    public static function connection_error($error_text)
    {
        http_response_code(500);
        echo "<!DOCTYPE html><html><style>.error-title{font-size:12.5rem}.error-title{color:#fff;font-size:8.125rem;line-height:1;margin-bottom:2.5rem;font-weight:300;text-stroke:1px transparent;display:block;text-shadow:0 1px 0 #ccc,0 2px 0 #c9c9c9,0 3px 0 #bbb,0 4px 0 #b9b9b9,0 5px 0 #aaa,0 6px 1px rgba(0,0,0,.1),0 0 5px rgba(0,0,0,.1),0 1px 3px rgba(0,0,0,.3),0 3px 5px rgba(0,0,0,.2),0 5px 10px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.2),0 20px 20px rgba(0,0,0,.15)}</style><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>CONNECTION Error</title><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'></head><body class='gray-bg'><div class='middle-box text-center animated fadeInDown'><h1 class='error-title'>SQL</h1><h3 class='font-bold'>MYSQL Connection Error</h3><div class='error-desc'>The server encountered something unexpected that didn't allow it to complete the request. We apologize.<br><br><br><p><b>$error_text</b></p></div></div><script src='https://code.jquery.com/jquery-3.7.1.min.js' integrity='sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo='crossorigin='anonymous'></script><script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script></body></html>";
        exit;
    }

    private function query_error($SQL)
    {
        $_SERVER['err'] = array();
        $_SERVER['err']['FILE'] = __FILE__;
        $_SERVER['err']['LINE'] = __LINE__;
        $_SERVER['err']['FUNCTION'] = __FUNCTION__;
        $_SERVER['err']['ERROR'] = mysqli_error($this->conn_list[$this->conn_name]);
        $_SERVER['err']['SQL'] = $SQL;

        http_response_code(500);

        $filenameDelimiter = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ? '\\' : '/';
        $filenameArr = explode($filenameDelimiter, $_SERVER['err']['FILE']);
        $filename = end($filenameArr);
        $line = $_SERVER['err']['LINE'];

        //---backtrace information for debugging.
        $_SERVER['err']['BACKTRACE'] = debug_backtrace();
        $errorLogPath = "temp/errors/$filename-$line.err";

        //---create folder recursively.
        makeFolder("temp/errors");

        $contentFile = fopen($errorLogPath, "w") or die("Unable to open file!");
        $errorDetails = "";

        foreach ($_SERVER['err'] as $key => $value) {
            $errorDetails .= is_array($value) ? "$key : " . json_encode($value, JSON_PRETTY_PRINT) . "\n\n" : "$key : $value\n\n";
        }

        fwrite($contentFile, $errorDetails);
        fclose($contentFile);

        $response = [
            'status' => "error",
            'text' => "Error S-1",
            'timer' => number_format(microtime(true) - strtotime($this->now), 4),
            'method' => 'modal'
        ];

        if (isDevopUser()) {
            $response['text'] = str_replace('[LINK]', getFullUrl() . "/$errorLogPath", "Error on S-1 <a href='[LINK]' target='_blank'>click here</a> to download error log.");
        }

        //---debugging and error display handling
        if ($this->break_on_error) {
            $this->connection_close();
            dd($response);
        } else {
            $json['status'] = "error";
            $json['text'] = $_SERVER['err']['ERROR'];

            return $json;
        }
    }
}
