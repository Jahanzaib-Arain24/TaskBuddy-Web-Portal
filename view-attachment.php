<?php
require_once __DIR__ . '/app/render_certificate.php';
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
display_credential('tbl_other_attachments', $file_id, 'Attachment Document');
