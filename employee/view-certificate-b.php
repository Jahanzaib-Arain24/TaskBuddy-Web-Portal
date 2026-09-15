<?php
require_once __DIR__ . '/../app/render_certificate.php';
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
display_credential('tbl_training', $file_id, 'Training & Workshop Certificate');
