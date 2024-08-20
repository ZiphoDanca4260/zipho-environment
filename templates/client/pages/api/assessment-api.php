<?php
ob_end_clean();

//---response function
// RE('test');

$data = $_POST['data'];

if (!empty($data)) {
    $data = json_decode($data, true);
}

//---the frontend will expect an object in this format: { status: 'success', text: 'message received', data: array() or object }
RE(['status' => 'success', 'text' => 'message received', 'data' => $data]);