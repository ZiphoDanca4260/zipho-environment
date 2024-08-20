<?php

function createUserParameters($users_user)
{
    global $now, $root, $db;
    $user_group_name = 'guest';
    $user_group_id = 5;
    $token = __token(__token());

    $item = array();
    $item['table'] = 'usr_users';
    $item['where'][] = "(user_mail IN ('$users_user') OR token IN ('" . $token . "'))";
    $item['cache'] = false;
    $item['join'][] = "LEFT JOIN usr_phones B ON usr_users.user_id = B.user_id AND B.phone_default = 1";
    $result = $db->query_read($item);

    if (!count($result['data'])) {

        //---get `usr_users` schema
        $schema = $db->query_read_schemas(["table_names" => ['usr_users']]);
        if (isset($schema['usr_users']) && count($schema['usr_users']['data'])) {

            $columnNames = array_column($schema['usr_users']['data'], 'COLUMN_NAME');
            $columnDefaults = array_column($schema['usr_users']['data'], 'COLUMN_DEFAULT');
            $root['user'] = array_combine($columnNames, $columnDefaults);
            unset($columnNames, $columnDefaults);
        }

        $root['user']['user_id'] = 0;
        $root['user']['user_mail'] = $users_user;
        $root['user']['user_group_name'] = $user_group_name;
        $root['user']['user_group_id'] = $user_group_id;
        $root['user']['token'] = $token;
    } else {
        $root['user'] = $result['data'][0];
    }

    $root['user']['token'] = $token;

    if ($root['user']['user_force_log_in']) {
        $item = array();
        $item['table'] = 'usr_users';
        $item['user_force_log_in'] = 0;
        $item['updated_at'] = $now;
        $item['where'][] = "user_id IN ('{$root['user']['user_id']}')";

        $result = $db->query_update($item);
        if ($result['status'] != 'success') {
            RE($result);
        }
        logOut();
        Redirect('/', false);
    }

    $item = array();
    $item['table'] = 'usr_temp';
    $item['where'][] = "(user_mail IN ('$users_user') OR token IN ('" . $token . "'))";
    $item['order'][] = 'user_mail ASC';
    $item['limit'] = 'LIMIT 0,2';
    $item['db_conn_name'] = 'default';
    $result = $db->query_read($item);

    if (!count($result['data'])) {

        $root['temp'] = array();

        //---get `usr_temps` schema
        $schema = $db->query_read_schemas(["table_names" => ['usr_temp']]);
        if (isset($schema['usr_temp']) && count($schema['usr_temp']['data'])) {

            $columnNames = array_column($schema['usr_temp']['data'], 'COLUMN_NAME');
            $columnDefaults = array_column($schema['usr_temp']['data'], 'COLUMN_DEFAULT');
            $root['temp'] = array_combine($columnNames, $columnDefaults);
            unset($columnNames, $columnDefaults);
        }

        $root['temp']['user_mail'] = $users_user;
        $root['temp']['user_id'] = 0;
        $root['temp']['user_group_id'] = $user_group_id;
        $root['temp']['session_id'] = $_SESSION['id'];
        $root['temp']['token'] = $token;

        $temp_admin_lang = "en";

        if (isset($root['temp']['temp_admin_lang'])) {
            $root['temp']['temp_admin_lang'] = $temp_admin_lang;
        }

        if (isset($root['temp']['temp_browser'])) {
            $root['temp']['temp_browser'] = $_SERVER['HTTP_USER_AGENT'];
        }

        if (isset($root['temp']['temp_remote_addr'])) {
            $root['temp']['temp_remote_addr'] = $_SERVER['REMOTE_ADDR'];
        }

        if (isset($root['temp']['temp_user_addr'])) {
            $root['temp']['temp_user_addr'] = GetIP();
        }

        if (isset($root['temp']['temp_referer'])) {
            $root['temp']['temp_referer'] = $_SERVER['HTTP_REFERER'];
        }

        if (isset($root['temp']['temp_settings'])) {
            $root['temp']['temp_settings'] = array();
        }

        if (isset($root['temp']['temp_created'])) {
            $root['temp']['temp_created'] = $now;
        }

        if (is_numeric($root['user']['user_id'])) {
            $root['temp']['user_id'] = $root['user']['user_id'];
        }

        $root['temp']['updated_at'] = $now;

        $item = $root['temp'];
        $item['table'] = 'usr_temp';
        unset($item['temp_id']);
        $result = $db->query_create($item);
        if ($result['status'] != 'success') {
            RE(json_encode($result));
        }

        if (isset($root['user']['db_connection']) && $root['user']['db_connection'] && $root['user']['db_connection'] != 'default') {
            $item['db_conn_name'] = 'default';
            $db->query_create($item);
            if ($result['status'] != 'success') {
                RE(json_encode($result));
            }
        }

        $root['temp']['temp_id'] = $result['id'];
        $root['user']['user_id'] = 0;
    } else {

        $root['temp'] = $result['data'][0];

        if (
            $root['temp']['user_id'] !== $root['user']['user_id'] ||
            $root['temp']['user_group_id'] !== $root['user']['user_group_id'] ||
            $root['temp']['user_mail'] !== $root['user']['user_mail']
        ) {

            $item = array();
            $item['table'] = 'usr_temp';
            $item['user_id'] = $root['user']['user_id'];
            $item['user_group_id'] = $root['user']['user_group_id'];
            $item['user_mail'] = $root['user']['user_mail'];
            $item['where'][] = "temp_id IN ('{$root['temp']['temp_id']}')";
            $db->query_update($item);

            if (isset($root['user']['db_connection']) && $root['user']['db_connection'] && $root['user']['db_connection'] != 'default') {
                $item['db_conn_name'] = "default";
                $db->query_update($item);
            }

            $root['temp']['user_id'] = $root['user']['user_id'];
            $root['temp']['user_group_id'] = $root['user']['user_group_id'];
            $root['temp']['user_mail'] = $root['user']['user_mail'];
        }
    }

    return $root;
}

//---set global values according to the user-agent and session.
if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = session_id();
}

if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = "unknown";
}

$root = createUserParameters($users_user);

if ($_SESSION['id'] != $root['temp']['session_id']) {
    $_SESSION['id'] = $root['temp']['session_id'];
}

if (!is_numeric($root['user']['user_id'])) {
    $root['user']['user_id'] = 0;
}

$item = [
    'table' => 'usr_user_group',
    'limit' => 'LIMIT 0,20',
    'where' => ["user_group_status IN ('published')"],
    'db_conn_name' => 'default'
];
$root['user_groups'] = $db->query_read($item)['data'];
