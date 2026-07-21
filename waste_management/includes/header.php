<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' : '' ?>SmartWaste Management System</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #1a7a4c;
            --primary-dark: #145c39;
            --primary-light: #e8f5ee;
            --secondary: #f0a500;
            --danger: #e53935;
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f6f9; }

        /* ---- SIDEBAR ---- */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, #0d3d26 0%, #1a7a4c 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: var(--secondary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 16px;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.6);
            font-size: 11px;
        }

        .sidebar-menu { padding: 16px 0; }

        .sidebar-section-label {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 24px 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--secondary);
        }

        .sidebar-link i { font-size: 18px; width: 20px; }

        .sidebar-link .badge-count {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 20px;
        }

        /* ---- TOPBAR ---- */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: #fff;
            box-shadow: 0 1px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 999;
            justify-content: space-between;
        }

        .topbar .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .topbar .notification-btn {
            position: relative;
        }

        .notification-dot {
            position: absolute;
            top: 2px; right: 2px;
            width: 10px; height: 10px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .user-avatar {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 600;
            font-size: 15px;
        }

        /* ---- MAIN CONTENT ---- */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--header-height);
            min-height: 100vh;
        }

        .content-area {
            padding: 28px 28px;
        }

        /* ---- STAT CARDS ---- */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        }

        .stat-card .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }

        .stat-card .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* ---- TABLE ---- */
        .data-table {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .data-table .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data-table .table-header h6 {
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
        }

        table thead th {
            background: #f8fafc;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border: none;
            padding: 12px 16px;
        }

        table tbody td {
            font-size: 14px;
            color: #374151;
            vertical-align: middle;
            padding: 12px 16px;
            border-color: #f3f4f6;
        }

        table tbody tr:hover { background: #f8fafc; }

        /* ---- FILL BAR ---- */
        .fill-bar {
            width: 100px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .fill-bar-inner {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* ---- CARDS ---- */
        .content-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: none;
        }

        /* ---- BADGES ---- */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        /* ---- FORM ---- */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e5e7eb;
            font-size: 14px;
            padding: 10px 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,122,76,0.1);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-sm { padding: 5px 12px; font-size: 13px; }

        /* ---- ALERTS ---- */
        .alert { border-radius: 10px; border: none; }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .topbar { left: 0; }
        }
    </style>
</head>
<body>
