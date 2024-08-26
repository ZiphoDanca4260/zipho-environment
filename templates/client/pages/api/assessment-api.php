<?php
ob_end_clean();

//---response function
// RE('test');

$data = $_POST['data'];

if (!empty($data)) {
    $data = json_decode($data, true);

    global $now, $root, $db;

    $root['blog_posts']['usr_blog_posts_image'] = $data['fields']['file'];

    $item = $root['blog_posts'];
    $item['table'] = 'usr_blog_posts';
    $result = $db->query_create($item);
}

//---the frontend will expect an object in this format: { status: 'success', text: 'message received', data: array() or object }
RE(['status' => 'success', 'text' => 'message received', 'data' => $data]);
