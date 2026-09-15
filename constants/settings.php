<?php
$actual_link = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';

$default_timezone = 'Asia/Karachi';
$smtp_host = '';
$smtp_user = '';
$smtp_pass = '';

$contact_mail = '';

$fb  = '#';
$tw = '#';
$ig = '#';

if (!function_exists('get_initials_avatar')) {
    function get_initials_avatar($fname, $lname = '', $size = 120) {
        $name = trim($fname . ' ' . $lname);
        $words = preg_split("/\s+/", $name);
        $initials = '';
        if (count($words) >= 2) {
            $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
        } elseif (!empty($words[0])) {
            $initials = mb_substr($words[0], 0, 2);
        } else {
            $initials = 'TB';
        }
        $initials = strtoupper($initials);
        
        $colors = [
            ['#4f46e5', '#6366f1'], // Indigo
            ['#0ea5e9', '#38bdf8'], // Sky
            ['#10b981', '#34d399'], // Emerald
            ['#f59e0b', '#fbbf24'], // Amber
            ['#ec4899', '#f472b6'], // Pink
            ['#8b5cf6', '#a78bfa'], // Violet
            ['#06b6d4', '#22d3ee'], // Cyan
            ['#ef4444', '#f87171'], // Red
            ['#1e293b', '#334155'], // Slate
            ['#0284c7', '#0369a1'], // Blue
        ];
        $idx = abs(crc32($name)) % count($colors);
        $c1 = $colors[$idx][0];
        $c2 = $colors[$idx][1];
        $id = 'g' . substr(md5($name), 0, 8);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 100 100">
            <defs>
                <linearGradient id="'.$id.'" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="'.$c1.'" />
                    <stop offset="100%" stop-color="'.$c2.'" />
                </linearGradient>
            </defs>
            <circle cx="50" cy="50" r="50" fill="url(#'.$id.')" />
            <text x="50" y="52" font-family="Plus Jakarta Sans, Inter, sans-serif" font-size="38" font-weight="700" fill="#ffffff" text-anchor="middle" dominant-baseline="central">'.$initials.'</text>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

if (!function_exists('parse_flexible_date')) {
    function parse_flexible_date($date_str) {
        if (empty($date_str)) {
            return new DateTime();
        }
        $date_str = trim($date_str);
        $formats = ['d/m/Y', 'm/d/Y', 'Y-m-d', 'Y/m/d', 'd-m-Y', 'F d, Y', 'd F Y', 'M d, Y'];
        foreach ($formats as $fmt) {
            $d = DateTime::createFromFormat($fmt, $date_str);
            if ($d !== false) {
                return $d;
            }
        }
        $ts = strtotime($date_str);
        if ($ts !== false && $ts > 0) {
            $d = new DateTime();
            $d->setTimestamp($ts);
            return $d;
        }
        return new DateTime();
    }
}

if (!function_exists('format_external_url')) {
    function format_external_url($url) {
        $url = trim($url ?? '');
        if (empty($url)) return '';
        if (preg_match("~^https?://~i", $url)) {
            return $url;
        }
        return "https://" . $url;
    }
}

if (!function_exists('format_task_text')) {
    function format_task_text($text) {
        if (empty($text)) return '';
        // Convert literal escaped newline strings into real newlines
        $text = str_replace(['\\r\\n', '\\n', '\\r', '\r\n', '\n', '\r'], "\n", $text);
        // Cleanly separate attached bullet points (e.g. "point.- Use" -> "point.\n- Use")
        $text = preg_replace('/([.!?;])\s*-\s+/', "$1\n- ", $text);
        $text = trim($text);
        
        // Trim excessive trailing <br> tags
        $text = preg_replace('/(<br\s*\/?>\s*)+$/i', '', $text);
        
        if (preg_match('/<(p|br|ul|ol|li|div|strong|b|em|i|a)\b/i', $text)) {
            // If already HTML, strip dangerous scripts but allow safe tags
            $allowed_tags = '<p><br><br/><b><strong><i><em><u><ul><ol><li><div><span><a>';
            $cleaned = strip_tags($text, $allowed_tags);
            return nl2br($cleaned);
        }
        
        return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
    }
}
?>