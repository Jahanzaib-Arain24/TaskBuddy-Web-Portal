<?php
function display_credential($table, $id, $type_label = "Certificate") {
    require __DIR__ . '/../constants/db_config.php';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM $table WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'><h3>Record Not Found</h3><p>The requested credential could not be found.</p></div>";
            exit;
        }

        $member_no = $row['member_no'] ?? '';
        
        // Fetch candidate details
        $stmtUser = $conn->prepare("SELECT first_name, last_name, avatar, city FROM tbl_users WHERE member_no = :mem LIMIT 1");
        $stmtUser->bindParam(':mem', $member_no);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $candidate_name = $user ? trim($user['first_name'] . ' ' . $user['last_name']) : 'Verified Tasker';
        $candidate_city = $user ? $user['city'] : 'Pakistan';

        // Extract title, institution, timeframe, and binary file
        $title = $row['course'] ?? $row['training'] ?? $row['title'] ?? 'Skill Competency';
        $institution = $row['institution'] ?? $row['issuer'] ?? 'Certified Vocational Institute';
        $timeframe = $row['timeframe'] ?? 'Verified Period';
        $level = $row['level'] ?? $type_label;
        $binary = $row['certificate'] ?? $row['attachment'] ?? null;

        // If actual valid PDF binary exists
        if (!empty($binary) && substr($binary, 0, 4) === '%PDF') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $title).'.pdf"');
            echo $binary;
            exit;
        }

        // If actual valid image binary exists
        if (!empty($binary) && (substr($binary, 0, 3) === "\xFF\xD8\xFF" || substr($binary, 1, 3) === 'PNG')) {
            $imgType = (substr($binary, 1, 3) === 'PNG') ? 'image/png' : 'image/jpeg';
            header("Content-Type: $imgType");
            echo $binary;
            exit;
        }

        // Render Luxury Digital Verified Certificate
        $cert_id = 'TB-CERT-' . strtoupper(substr(md5($table . $id . $member_no), 0, 10));
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskBuddy Verified Certificate — <?php echo htmlspecialchars($candidate_name); ?></title>
    <link rel="shortcut icon" href="images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #0f172a;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            color: #1e293b;
        }
        .cert-actions {
            width: 100%;
            max-width: 860px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .cert-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-back {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }
        .btn-back:hover { background: rgba(255, 255, 255, 0.2); }
        .btn-print {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.6); }

        .cert-card {
            background: #ffffff;
            width: 100%;
            max-width: 860px;
            border-radius: 20px;
            padding: 45px 50px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            border: 8px double #e2e8f0;
        }
        .cert-border-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #06b6d4, #f59e0b, #4f46e5);
        }
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .cert-logo {
            height: 40px;
            width: auto;
        }
        .cert-badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eef2ff;
            color: #4f46e5;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 700;
            border: 1px solid rgba(79, 70, 229, 0.2);
        }
        .cert-body {
            text-align: center;
            padding: 10px 20px 20px 20px;
        }
        .cert-pretitle {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 12px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .cert-main-title {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }
        .cert-for {
            font-size: 15px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 10px;
        }
        .cert-recipient {
            font-size: 28px;
            font-weight: 800;
            color: #4f46e5;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
            text-decoration: underline;
            text-decoration-color: #cbd5e1;
            text-underline-offset: 8px;
        }
        .cert-desc {
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
            max-width: 620px;
            margin: 20px auto 25px auto;
        }
        .cert-highlight {
            font-weight: 700;
            color: #0f172a;
        }
        .cert-grid-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 24px;
            margin: 30px auto;
            text-align: left;
        }
        .cert-meta-item strong {
            display: block;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .cert-meta-item span {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .cert-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #f1f5f9;
        }
        .cert-seal {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
            border: 3px dashed rgba(255, 255, 255, 0.7);
        }
        .cert-seal i { font-size: 22px; margin-bottom: 2px; }
        .cert-seal span { font-size: 8.5px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; }

        .cert-signature-box {
            text-align: center;
            width: 200px;
        }
        .cert-sig-line {
            height: 1.5px;
            background: #94a3b8;
            margin-bottom: 6px;
        }
        .cert-sig-title {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        /* --- Mobile Responsive Certificate Styling (< 650px) --- */
        @media (max-width: 650px) {
            body {
                padding: 15px 10px;
            }
            .cert-actions {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            .cert-btn {
                width: 100%;
                justify-content: center;
            }
            .cert-card {
                padding: 24px 16px;
                border-width: 4px;
                border-radius: 16px;
            }
            .cert-header {
                flex-direction: column;
                gap: 12px;
                align-items: center;
                text-align: center;
                margin-bottom: 18px;
            }
            .cert-body {
                padding: 5px 0 10px 0;
            }
            .cert-main-title {
                font-size: 24px;
                margin-bottom: 14px;
            }
            .cert-recipient {
                font-size: 22px;
                word-break: break-word;
            }
            .cert-desc {
                font-size: 13.5px;
                line-height: 1.55;
                margin: 14px auto 18px auto;
            }
            .cert-grid-meta {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 14px 16px;
                margin: 18px 0;
                width: 100%;
            }
            .cert-footer {
                flex-direction: column;
                gap: 20px;
                align-items: center;
                text-align: center;
                margin-top: 20px;
                padding-top: 18px;
            }
            .cert-seal {
                margin: 0 auto;
            }
            .cert-signature-box {
                margin: 0 auto;
                width: 100%;
                max-width: 200px;
            }
        }

        @media print {
            body { background: #ffffff; padding: 0; }
            .cert-actions { display: none; }
            .cert-card { box-shadow: none; border: 4px solid #cbd5e1; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="cert-actions">
        <a href="employee-detail.php?empid=<?php echo urlencode($member_no); ?>" class="cert-btn btn-back" onclick="return handleCertBack(event);"><i class="fa fa-arrow-left"></i> Back to Seeker Profile</a>
        <button class="cert-btn btn-print" onclick="window.print();"><i class="fa fa-print"></i> Print / Save PDF</button>
    </div>

    <div class="cert-card">
        <div class="cert-border-accent"></div>

        <div class="cert-header">
            <img src="logo2.png" alt="TaskBuddy" class="cert-logo">
            <div class="cert-badge-tag">
                <i class="fa fa-check-circle"></i> Officially Verified Credential
            </div>
        </div>

        <div class="cert-body">
            <div class="cert-pretitle">Verified Skill & Qualification Record</div>
            <h1 class="cert-main-title">Certificate of Competency</h1>
            <p class="cert-for">This is to officially certify that</p>
            
            <div class="cert-recipient"><?php echo htmlspecialchars($candidate_name); ?></div>

            <p class="cert-desc">
                Has fulfilled all recognized standards and background verification for 
                <span class="cert-highlight"><?php echo htmlspecialchars($title); ?></span> 
                conducted through <span class="cert-highlight"><?php echo htmlspecialchars($institution); ?></span>.
            </p>

            <div class="cert-grid-meta">
                <div class="cert-meta-item">
                    <strong>Qualification Level</strong>
                    <span><?php echo htmlspecialchars($level); ?></span>
                </div>
                <div class="cert-meta-item">
                    <strong>Timeframe / Year</strong>
                    <span><?php echo htmlspecialchars($timeframe); ?></span>
                </div>
                <div class="cert-meta-item">
                    <strong>Issuing Body / Location</strong>
                    <span><?php echo htmlspecialchars($institution . ', ' . $candidate_city); ?></span>
                </div>
            </div>
        </div>

        <div class="cert-footer">
            <div style="text-align: left;">
                <p style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Credential ID</p>
                <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-size: 12px; color: #0f172a; font-weight: 700;"><?php echo $cert_id; ?></code>
                <p style="font-size: 11px; color: #10b981; font-weight: 600; margin-top: 6px;"><i class="fa fa-shield"></i> Verified on TaskBuddy Network</p>
            </div>

            <div class="cert-seal">
                <i class="fa fa-check-square-o"></i>
                <span>Verified</span>
            </div>

            <div class="cert-signature-box">
                <div style="font-family:'Playfair Display', serif; font-style:italic; font-size:18px; color:#4f46e5; margin-bottom:4px;">TaskBuddy Verification</div>
                <div class="cert-sig-line"></div>
                <div class="cert-sig-title">Registrar of Verified Taskers</div>
            </div>
    </div>
</div>

<script>
function handleCertBack(e) {
    try {
        if (window.opener && !window.opener.closed) {
            e.preventDefault();
            window.close();
            return false;
        }
    } catch(err) {}

    if (window.history.length > 1 && document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
        e.preventDefault();
        window.history.back();
        return false;
    }

    return true;
}
</script>

</body>
</html>
        <?php
        exit;
    } catch (PDOException $e) {
        echo "Database Error: " . htmlspecialchars($e->getMessage());
        exit;
    }
}
