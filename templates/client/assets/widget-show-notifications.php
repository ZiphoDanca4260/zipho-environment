<?php
// RE($_SESSION['messages']);
if (isset($_SESSION['messages'])) {
    echo '<main class="container flex-grow-1">';
    foreach ($_SESSION['messages'] as $messagetype => $value) {
        # code...
        if (is_array($value) && count($value)) {
            foreach ($value as $index => $message) {
                # code...
?>
                <div class="alert alert-<?= $messagetype?> alert-dismissible fade show" role="alert">
                    <i class="fa-light fa-circle-exclamation fa-2x text-<?= $messagetype?>"></i>
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

<?php
            }
        }
    }
    unset($_SESSION['clearmessage'] );
    $_SESSION['clearmessage'] = true;
    echo '</main>';
}
