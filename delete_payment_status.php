<?php
// delete_payment_status.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists('payment_status.txt')) {
        unlink('payment_status.txt');
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'file_not_found']);
    }
} else {
    echo json_encode(['status' => 'invalid_request']);
}
?>
