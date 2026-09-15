<?php
require_once __DIR__ . '/../../constants/db_config.php';
require_once __DIR__ . '/../constants/check-login.php';
require_once __DIR__ . '/../../constants/settings.php';

if (empty($user_online) || $user_online !== true) {
    header("location:../");
    exit();
}

if ($myrole !== "employee") {
    header("location:../");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Fetch User details
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :myid LIMIT 1");
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User record not found.");
    }

    $fname     = $user['first_name'] ?? '';
    $lname     = $user['last_name'] ?? '';
    $fullname  = trim($fname . ' ' . $lname);
    if (empty($fullname)) $fullname = 'Task Seeker';

    $title     = !empty($user['title']) ? $user['title'] : 'Verified Professional & Specialist';
    $email     = $user['email'] ?? '';
    $phone     = $user['phone'] ?? '';
    $gender    = $user['gender'] ?? '';
    $city      = $user['city'] ?? '';
    $country   = $user['country'] ?? '';
    $street    = $user['street'] ?? '';
    $zip       = $user['zip'] ?? '';
    $about     = !empty($user['about']) ? $user['about'] : 'Experienced and dedicated professional with a proven track record of delivering top-tier services and quality task execution across diverse domains.';
    $avatarBlob = $user['avatar'] ?? null;
    $bdate     = $user['bdate'] ?? '';
    $bmonth    = $user['bmonth'] ?? '';
    $byear     = $user['byear'] ?? '';

    $full_location = implode(', ', array_filter([$street, $city, $country, $zip]));
    if (empty($full_location)) $full_location = 'Pakistan';

    // Avatar image
    if (!empty($avatarBlob)) {
        $avatar_src = 'data:image/jpeg;base64,' . base64_encode($avatarBlob);
    } else {
        $avatar_src = get_initials_avatar($fname ?: 'T', $lname ?: 'B', 160);
    }

    // 2. Fetch Experience
    $stmtExp = $conn->prepare("SELECT * FROM tbl_experience WHERE member_no = :myid ORDER BY id DESC");
    $stmtExp->bindParam(':myid', $myid);
    $stmtExp->execute();
    $experiences = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

    // 3. Fetch Academic Qualifications
    $stmtAca = $conn->prepare("SELECT * FROM tbl_academic_qualification WHERE member_no = :myid ORDER BY id DESC");
    $stmtAca->bindParam(':myid', $myid);
    $stmtAca->execute();
    $academics = $stmtAca->fetchAll(PDO::FETCH_ASSOC);

    // 4. Fetch Professional Qualifications
    $stmtProf = $conn->prepare("SELECT * FROM tbl_professional_qualification WHERE member_no = :myid ORDER BY id DESC");
    $stmtProf->bindParam(':myid', $myid);
    $stmtProf->execute();
    $prof_quals = $stmtProf->fetchAll(PDO::FETCH_ASSOC);

    // 5. Fetch Training & Workshops
    $stmtTrain = $conn->prepare("SELECT * FROM tbl_training WHERE member_no = :myid ORDER BY id DESC");
    $stmtTrain->bindParam(':myid', $myid);
    $stmtTrain->execute();
    $trainings = $stmtTrain->fetchAll(PDO::FETCH_ASSOC);

    // 6. Fetch Languages
    $stmtLang = $conn->prepare("SELECT * FROM tbl_language WHERE member_no = :myid ORDER BY id ASC");
    $stmtLang->bindParam(':myid', $myid);
    $stmtLang->execute();
    $languages = $stmtLang->fetchAll(PDO::FETCH_ASSOC);

    // 7. Fetch Referees
    $stmtRef = $conn->prepare("SELECT * FROM tbl_referees WHERE member_no = :myid ORDER BY id ASC");
    $stmtRef->bindParam(':myid', $myid);
    $stmtRef->execute();
    $referees = $stmtRef->fetchAll(PDO::FETCH_ASSOC);

    // 8. Fetch Other Attachments
    $stmtAtt = $conn->prepare("SELECT * FROM tbl_other_attachments WHERE member_no = :myid ORDER BY id ASC");
    $stmtAtt->bindParam(':myid', $myid);
    $stmtAtt->execute();
    $attachments = $stmtAtt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Function to safely render rich text without escaping valid HTML tags
function render_rich_content($html) {
    if (empty($html)) return '';
    // Allow standard safe formatting tags
    $allowed_tags = '<b><strong><i><em><u><a><p><br><ul><ol><li><span><div><font><small><big>';
    return strip_tags($html, $allowed_tags);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($fullname); ?> — Executive Curriculum Vitae | TaskBuddy</title>
    <link rel="shortcut icon" href="../../images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --accent-gold: #d97706;
            --accent-green: #059669;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0b0f19;
            color: var(--slate-800);
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* CV Top Controls */
        .cv-controls {
            width: 100%;
            max-width: 960px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .cv-controls-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cv-badge-top {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
            color: #60a5fa;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
            color: #ffffff;
        }

        .btn-secondary-action {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-secondary-action:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        /* CV Sheet / Paper Container */
        .cv-paper {
            width: 100%;
            max-width: 960px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
        }

        /* Luxury Top Header Banner */
        .cv-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #ffffff;
            padding: 45px 50px 40px;
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid var(--primary);
        }

        .cv-header::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(37, 99, 235, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .cv-header-grid {
            display: flex;
            align-items: center;
            gap: 32px;
            position: relative;
            z-index: 2;
        }

        .cv-avatar-box {
            position: relative;
            flex-shrink: 0;
        }

        .cv-avatar-box img {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            background: #ffffff;
        }

        .cv-verified-badge-icon {
            position: absolute;
            bottom: -6px;
            right: -6px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 3px solid #0f172a;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .cv-header-info {
            flex-grow: 1;
        }

        .cv-taskbuddy-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #93c5fd;
            margin-bottom: 8px;
        }

        .cv-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 34px;
            font-weight: 700;
            line-height: 1.15;
            color: #ffffff;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .cv-title {
            font-size: 17px;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .cv-contact-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
        }

        .cv-contact-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: #94a3b8;
        }

        .cv-contact-pill i {
            color: #38bdf8;
            font-size: 13px;
        }

        /* CV Body Two-Column Layout */
        .cv-body {
            display: grid;
            grid-template-columns: 310px 1fr;
            min-height: 600px;
        }

        /* Left Sidebar */
        .cv-sidebar {
            background: var(--slate-50);
            padding: 35px 30px;
            border-right: 1px solid var(--slate-200);
        }

        /* Main Content */
        .cv-main {
            padding: 35px 40px;
            background: #ffffff;
        }

        /* Section Headings */
        .cv-section {
            margin-bottom: 30px;
        }

        .cv-section:last-child {
            margin-bottom: 0;
        }

        .cv-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 800;
            color: var(--slate-900);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--slate-200);
            position: relative;
        }

        .cv-section-title::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 45px;
            height: 2px;
            background: var(--primary);
        }

        .cv-section-title i {
            color: var(--primary);
            font-size: 15px;
        }

        /* Professional Summary */
        .cv-summary-text {
            font-size: 14.5px;
            line-height: 1.7;
            color: var(--slate-700);
            background: var(--primary-light);
            border-left: 3px solid var(--primary);
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            word-break: break-word;
        }

        .cv-summary-text p {
            margin-bottom: 8px;
        }

        .cv-summary-text p:last-child {
            margin-bottom: 0;
        }

        .cv-summary-text a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.2s;
        }

        .cv-summary-text a:hover {
            color: var(--primary-dark);
        }

        .cv-summary-text b,
        .cv-summary-text strong {
            font-weight: 700;
            color: var(--slate-900);
        }

        /* Timeline Items (Experience, Education, Training) */
        .cv-timeline {
            position: relative;
            padding-left: 24px;
        }

        .cv-timeline::before {
            content: "";
            position: absolute;
            top: 6px;
            bottom: 6px;
            left: 5px;
            width: 2px;
            background: var(--slate-200);
        }

        .cv-timeline-item {
            position: relative;
            margin-bottom: 24px;
        }

        .cv-timeline-item:last-child {
            margin-bottom: 0;
        }

        .cv-timeline-dot {
            position: absolute;
            left: -24px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid #ffffff;
            box-shadow: 0 0 0 2px var(--primary);
        }

        .cv-timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 4px;
        }

        .cv-item-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--slate-900);
        }

        .cv-item-badge {
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
            background: var(--primary-light);
            padding: 2px 10px;
            border-radius: 9999px;
        }

        .cv-item-subtitle {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--slate-600);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cv-item-desc {
            font-size: 13.5px;
            line-height: 1.6;
            color: var(--slate-600);
            word-break: break-word;
        }

        .cv-cred-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            margin-top: 6px;
            background: #ffffff;
            border: 1px solid #bfdbfe;
            padding: 3px 10px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .cv-cred-link:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Sidebar Elements */
        .cv-side-info-list {
            list-style: none;
        }

        .cv-side-info-item {
            margin-bottom: 12px;
            font-size: 13px;
        }

        .cv-side-info-label {
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .cv-side-info-val {
            font-weight: 600;
            color: var(--slate-800);
        }

        /* Languages in Sidebar */
        .cv-lang-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 10px;
        }

        .cv-lang-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--slate-900);
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .cv-lang-skills {
            font-size: 11.5px;
            color: var(--slate-500);
            display: flex;
            gap: 8px;
        }

        .cv-lang-tag {
            background: var(--slate-100);
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* Referees Card */
        .cv-ref-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 10px;
        }

        .cv-ref-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--slate-900);
        }

        .cv-ref-title {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .cv-ref-contact {
            font-size: 12px;
            color: var(--slate-600);
        }

        /* Verified Footer Seal */
        .cv-footer-seal {
            background: var(--slate-50);
            border-top: 1px solid var(--slate-200);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: var(--slate-500);
        }

        .cv-seal-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cv-seal-badge {
            background: #10b981;
            color: #ffffff;
            font-weight: 700;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Print Media Styles */
        @media print {
            @page {
                size: A4 portrait;
                margin: 8mm 8mm 8mm 8mm;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                color: #1e293b !important;
            }

            .cv-controls {
                display: none !important;
            }

            .cv-paper {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                border: 1px solid #cbd5e1 !important;
            }

            .cv-header {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
                color: #ffffff !important;
                padding: 35px 40px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .cv-header-grid {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                text-align: left !important;
                gap: 24px !important;
            }

            .cv-name {
                color: #ffffff !important;
            }

            .cv-contact-pills {
                display: flex !important;
                justify-content: flex-start !important;
            }

            .cv-body {
                display: grid !important;
                grid-template-columns: 280px 1fr !important;
                min-height: auto !important;
            }

            .cv-sidebar {
                background: #f8fafc !important;
                padding: 25px 20px !important;
                border-right: 1px solid #e2e8f0 !important;
                border-bottom: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .cv-main {
                padding: 25px 30px !important;
                background: #ffffff !important;
            }

            .cv-section, .cv-item-card, .cv-header {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .btn-action, .cv-cred-link {
                text-decoration: none !important;
            }
        }

        @media screen and (max-width: 768px) {
            .cv-header-grid {
                flex-direction: column;
                text-align: center;
            }

            .cv-contact-pills {
                justify-content: center;
            }

            .cv-body {
                grid-template-columns: 1fr;
            }

            .cv-sidebar {
                border-right: none;
                border-bottom: 1px solid var(--slate-200);
            }
        }
    </style>
</head>
<body>

    <!-- CV Top Navigation / Toolbar -->
    <div class="cv-controls">
        <div class="cv-controls-left">
            <span class="cv-badge-top">
                <i class="fa fa-shield"></i> Verified TaskBuddy Profile
            </span>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="../" class="btn-action btn-secondary-action">
                <i class="fa fa-arrow-left"></i> Dashboard
            </a>
            <button onclick="window.print()" class="btn-action btn-primary-action">
                <i class="fa fa-print"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Printable Executive CV Paper -->
    <div class="cv-paper">
        
        <!-- Header Banner -->
        <div class="cv-header">
            <div class="cv-header-grid">
                <div class="cv-avatar-box">
                    <img src="<?php echo $avatar_src; ?>" alt="<?php echo htmlspecialchars($fullname); ?>">
                    <div class="cv-verified-badge-icon" title="TaskBuddy Verified Candidate">
                        <i class="fa fa-check"></i>
                    </div>
                </div>
                <div class="cv-header-info">
                    <div class="cv-taskbuddy-tag">
                        <i class="fa fa-id-card-o"></i> TaskBuddy Certified Specialist
                    </div>
                    <h1 class="cv-name"><?php echo htmlspecialchars($fullname); ?></h1>
                    <div class="cv-title"><?php echo htmlspecialchars($title); ?></div>
                    <div class="cv-contact-pills">
                        <?php if (!empty($email)): ?>
                        <div class="cv-contact-pill">
                            <i class="fa fa-envelope-o"></i> <?php echo htmlspecialchars($email); ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($phone)): ?>
                        <div class="cv-contact-pill">
                            <i class="fa fa-phone"></i> <?php echo htmlspecialchars($phone); ?>
                        </div>
                        <?php endif; ?>
                        <div class="cv-contact-pill">
                            <i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($full_location); ?>
                        </div>
                        <?php if (!empty($gender)): ?>
                        <div class="cv-contact-pill">
                            <i class="fa fa-user-o"></i> <?php echo htmlspecialchars($gender); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- CV Two-Column Grid -->
        <div class="cv-body">
            
            <!-- Left Meta Sidebar -->
            <div class="cv-sidebar">
                
                <!-- Personal Details Section -->
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-info-circle"></i> Details</h3>
                    <ul class="cv-side-info-list">
                        <li class="cv-side-info-item">
                            <div class="cv-side-info-label">Member ID</div>
                            <div class="cv-side-info-val"><?php echo htmlspecialchars($myid); ?></div>
                        </li>
                        <li class="cv-side-info-item">
                            <div class="cv-side-info-label">Location</div>
                            <div class="cv-side-info-val"><?php echo htmlspecialchars($city . ($country ? ', ' . $country : '')); ?></div>
                        </li>
                        <?php if (!empty($byear)): ?>
                        <li class="cv-side-info-item">
                            <div class="cv-side-info-label">Year of Birth</div>
                            <div class="cv-side-info-val"><?php echo htmlspecialchars($byear); ?></div>
                        </li>
                        <?php endif; ?>
                        <li class="cv-side-info-item">
                            <div class="cv-side-info-label">Profile Status</div>
                            <div class="cv-side-info-val" style="color: #059669;"><i class="fa fa-check-circle"></i> Active & Verified</div>
                        </li>
                    </ul>
                </div>

                <!-- Languages Section -->
                <?php if (!empty($languages)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-language"></i> Languages</h3>
                    <?php foreach ($languages as $lang): ?>
                    <div class="cv-lang-card">
                        <div class="cv-lang-name">
                            <span><?php echo htmlspecialchars($lang['language']); ?></span>
                            <span style="font-size: 11px; color: var(--primary);"><?php echo htmlspecialchars($lang['speak']); ?></span>
                        </div>
                        <div class="cv-lang-skills">
                            <span class="cv-lang-tag">Speak: <?php echo htmlspecialchars($lang['speak']); ?></span>
                            <span class="cv-lang-tag">Read: <?php echo htmlspecialchars($lang['reading']); ?></span>
                            <span class="cv-lang-tag">Write: <?php echo htmlspecialchars($lang['writing']); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Attachments & Documents -->
                <?php if (!empty($attachments)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-paperclip"></i> Documents</h3>
                    <?php foreach ($attachments as $att): ?>
                    <div class="cv-ref-card">
                        <div class="cv-ref-name"><?php echo htmlspecialchars($att['title']); ?></div>
                        <div class="cv-ref-title"><?php echo htmlspecialchars($att['issuer']); ?></div>
                        <a target="_blank" href="../view-attachment.php?id=<?php echo $att['id']; ?>" class="cv-cred-link">
                            <i class="fa fa-external-link"></i> View Document
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Referees Section -->
                <?php if (!empty($referees)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-users"></i> References</h3>
                    <?php foreach ($referees as $ref): ?>
                    <div class="cv-ref-card">
                        <div class="cv-ref-name"><?php echo htmlspecialchars($ref['ref_name']); ?></div>
                        <div class="cv-ref-title"><?php echo htmlspecialchars($ref['ref_title']); ?> — <?php echo htmlspecialchars($ref['institution']); ?></div>
                        <?php if (!empty($ref['ref_mail'])): ?>
                        <div class="cv-ref-contact"><i class="fa fa-envelope-o"></i> <?php echo htmlspecialchars($ref['ref_mail']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($ref['ref_phone'])): ?>
                        <div class="cv-ref-contact"><i class="fa fa-phone"></i> <?php echo htmlspecialchars($ref['ref_phone']); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>

            <!-- Main Professional Content -->
            <div class="cv-main">
                
                <!-- Professional Summary -->
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-user-circle-o"></i> Executive Summary</h3>
                    <div class="cv-summary-text">
                        <?php echo render_rich_content($about); ?>
                    </div>
                </div>

                <!-- Employment Experience -->
                <?php if (!empty($experiences)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-briefcase"></i> Work Experience</h3>
                    <div class="cv-timeline">
                        <?php foreach ($experiences as $exp): ?>
                        <div class="cv-timeline-item">
                            <div class="cv-timeline-dot"></div>
                            <div class="cv-timeline-header">
                                <div class="cv-item-title"><?php echo htmlspecialchars($exp['title']); ?></div>
                                <div class="cv-item-badge"><?php echo htmlspecialchars($exp['start_date'] . ' - ' . $exp['end_date']); ?></div>
                            </div>
                            <div class="cv-item-subtitle">
                                <i class="fa fa-building-o"></i> <?php echo htmlspecialchars($exp['institution']); ?>
                                <?php if (!empty($exp['supervisor'])): ?>
                                <span style="font-weight: 400; font-size: 12px; color: var(--slate-500);">(Supervisor: <?php echo htmlspecialchars($exp['supervisor']); ?>)</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($exp['duties'])): ?>
                            <div class="cv-item-desc"><?php echo render_rich_content($exp['duties']); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Professional Qualifications -->
                <?php if (!empty($prof_quals)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-trophy"></i> Professional Qualifications</h3>
                    <div class="cv-timeline">
                        <?php foreach ($prof_quals as $pq): ?>
                        <div class="cv-timeline-item">
                            <div class="cv-timeline-dot"></div>
                            <div class="cv-timeline-header">
                                <div class="cv-item-title"><?php echo htmlspecialchars($pq['title']); ?></div>
                                <div class="cv-item-badge"><?php echo htmlspecialchars($pq['timeframe']); ?></div>
                            </div>
                            <div class="cv-item-subtitle">
                                <i class="fa fa-institution"></i> <?php echo htmlspecialchars($pq['institution'] . ($pq['country'] ? ', ' . $pq['country'] : '')); ?>
                            </div>
                            <a target="_blank" href="../view-certificate-c.php?id=<?php echo $pq['id']; ?>" class="cv-cred-link">
                                <i class="fa fa-certificate"></i> View Verified Certificate
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Academic Qualifications -->
                <?php if (!empty($academics)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-graduation-cap"></i> Academic Education</h3>
                    <div class="cv-timeline">
                        <?php foreach ($academics as $aca): ?>
                        <div class="cv-timeline-item">
                            <div class="cv-timeline-dot"></div>
                            <div class="cv-timeline-header">
                                <div class="cv-item-title"><?php echo htmlspecialchars($aca['course']); ?> (<?php echo htmlspecialchars($aca['level']); ?>)</div>
                                <div class="cv-item-badge"><?php echo htmlspecialchars($aca['timeframe']); ?></div>
                            </div>
                            <div class="cv-item-subtitle">
                                <i class="fa fa-university"></i> <?php echo htmlspecialchars($aca['institution'] . ($aca['country'] ? ', ' . $aca['country'] : '')); ?>
                            </div>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a target="_blank" href="../view-certificate.php?id=<?php echo $aca['id']; ?>" class="cv-cred-link">
                                    <i class="fa fa-certificate"></i> Degree Certificate
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Training & Workshops -->
                <?php if (!empty($trainings)): ?>
                <div class="cv-section">
                    <h3 class="cv-section-title"><i class="fa fa-cogs"></i> Training & Workshops</h3>
                    <div class="cv-timeline">
                        <?php foreach ($trainings as $tr): ?>
                        <div class="cv-timeline-item">
                            <div class="cv-timeline-dot"></div>
                            <div class="cv-timeline-header">
                                <div class="cv-item-title"><?php echo htmlspecialchars($tr['training']); ?></div>
                                <div class="cv-item-badge"><?php echo htmlspecialchars($tr['timeframe']); ?></div>
                            </div>
                            <div class="cv-item-subtitle">
                                <i class="fa fa-shield"></i> <?php echo htmlspecialchars($tr['institution']); ?>
                            </div>
                            <a target="_blank" href="../view-certificate-b.php?id=<?php echo $tr['id']; ?>" class="cv-cred-link">
                                <i class="fa fa-certificate"></i> Verified Workshop Credential
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

        </div>

        <!-- Footer Verified Seal -->
        <div class="cv-footer-seal">
            <div class="cv-seal-left">
                <span class="cv-seal-badge"><i class="fa fa-check-circle"></i> TaskBuddy Verified</span>
                <span>Digitally verified professional portfolio and curriculum vitae.</span>
            </div>
            <div>
                Generated on <?php echo date('F d, Y'); ?>
            </div>
        </div>

    </div>

</body>
</html>