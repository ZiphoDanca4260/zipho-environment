<?php

function loadLang($lang_current = 'en')
{
    global $template, $root;

    $language_file_path = "./{$template}/languages/{$lang_current}.json";

    // Ensure that $root['temp']['notes'] is an array.
    if (!isset($root['temp']['notes']) || !is_array($root['temp']['notes'])) {
        $root['temp']['notes'] = [];
    }

    // Check if the language file exists, fall back to the default 'en' if it does not.
    if (!file_exists($language_file_path)) {
        $root['temp']['notes']['temp_lang_info'] = "{$language_file_path} does not exist, fallback to default language 'en'.";
        $lang_current = 'en';
        $root['temp']['temp_lang'] = $lang_current;
        $language_file_path = "./{$template}/languages/{$lang_current}.json";
    }

    $file_contents = file_get_contents($language_file_path);
    $file_contents_replaced = replaceConstants($file_contents);

    return json_decode($file_contents_replaced, true);
}

//---set global variable lang.
$lang = loadLang();