<?php
require_once __DIR__ . '/../Components/funcs.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { align-items: flex-start; padding: 40px 16px; }
        .dashboard { max-width: 560px; width: 100%; margin: 0 auto; }
        .top-bar {
            background: #1565c0;
            color: #fff;
            border-radius: 12px;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(21,101,192,.18);
        }
        .top-bar h2 { font-size: 18px; font-weight: 700; }
        .top-bar span { font-size: 13px; opacity: .75; display: block; }
        .logout-btn {
            background: rgba(255,255,255,.18);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }
        .logout-btn:hover { background: rgba(255,255,255,.3); }
        .welcome-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(25,118,210,.10);
            padding: 32px 28px;
            text-align: center;
        }
        .welcome-card h3 { color: #1565c0; font-size: 20px; margin-bottom: 8px; }
        .welcome-card p  { color: #64b5f6; font-size: 14px; }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="top-bar">
            <div>
                <h2>Dashboard</h2>
                <span>Voedselbank Maaskantje</span>
            </div>
            <a href="../Components/logout.php" class="logout-btn">Uitloggen</a>
        </div>
        <div class="welcome-card">
            <h3>Hallo, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h3>
            <p>Je bent succesvol ingelogd.</p>
        </div>
    </div>
</body>
</html>
