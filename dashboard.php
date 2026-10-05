
<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];


/* =====================================================
   USER INITIALS
===================================================== */

$name_parts = explode(" ", trim($user_name));

if (count($name_parts) >= 2) {

    $initials =
        strtoupper(
            substr($name_parts[0], 0, 1) .
            substr($name_parts[count($name_parts) - 1], 0, 1)
        );

} else {

    $initials =
        strtoupper(
            substr($user_name, 0, 2)
        );
}


/* =====================================================
   COMPLAINT STATISTICS
===================================================== */

$total = 0;
$pending = 0;
$in_progress = 0;
$resolved = 0;
$rejected = 0;

$count_sql = "
    SELECT
        COUNT(*) AS total,
        SUM(status = 'Pending') AS pending,
        SUM(status = 'In Progress') AS in_progress,
        SUM(status = 'Resolved') AS resolved,
        SUM(status = 'Rejected') AS rejected
    FROM complaints
    WHERE user_id='$user_id'
";

$count_result = mysqli_query($conn, $count_sql);

if ($count_result) {

    $count_data = mysqli_fetch_assoc($count_result);

    $total = $count_data['total'] ?? 0;
    $pending = $count_data['pending'] ?? 0;
    $in_progress = $count_data['in_progress'] ?? 0;
    $resolved = $count_data['resolved'] ?? 0;
    $rejected = $count_data['rejected'] ?? 0;
}


/* =====================================================
   STATUS PERCENTAGES
===================================================== */

if ($total > 0) {

    $pending_percent =
        round(($pending / $total) * 100);

    $progress_percent =
        round(($in_progress / $total) * 100);

    $resolved_percent =
        round(($resolved / $total) * 100);

    $rejected_percent =
        round(($rejected / $total) * 100);

} else {

    $pending_percent = 0;
    $progress_percent = 0;
    $resolved_percent = 0;
    $rejected_percent = 0;
}


/* =====================================================
   RECENT COMPLAINTS
===================================================== */

$recent_sql = "
    SELECT
        id,
        title,
        category,
        priority,
        status,
        created_at
    FROM complaints
    WHERE user_id='$user_id'
    ORDER BY id DESC
    LIMIT 6
";

$recent_result = mysqli_query($conn, $recent_sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Employee Dashboard | Office Complaint System</title>


<style>

/* =====================================================
   GLOBAL
===================================================== */

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background: #F8FAFC;

    color: #0F172A;
}

a {
    text-decoration: none;
}


/* =====================================================
   TOPBAR
===================================================== */

.topbar {

    height: 72px;

    background: white;

    border-bottom:
        1px solid #E2E8F0;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 4%;

    position: sticky;

    top: 0;

    z-index: 100;
}


.brand {

    display: flex;

    align-items: center;

    gap: 11px;
}


.brand-logo {

    width: 40px;

    height: 40px;

    border-radius: 10px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #1E3A8A,
            #2563EB
        );

    color: white;

    font-size: 12px;

    font-weight: 800;
}


.brand-text strong {

    display: block;

    font-size: 15px;

    color: #0F172A;
}


.brand-text span {

    display: block;

    margin-top: 3px;

    font-size: 10px;

    color: #94A3B8;
}


/* =====================================================
   PROFILE
===================================================== */

.profile-area {
    position: relative;
}


.profile-btn {

    border: none;

    background: transparent;

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 5px 8px;

    border-radius: 10px;

    cursor: pointer;

    transition: 0.2s;
}


.profile-btn:hover,
.profile-btn.active {

    background: #F1F5F9;
}


.profile-avatar {

    width: 40px;

    height: 40px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #1D4ED8,
            #06B6D4
        );

    color: white;

    font-size: 12px;

    font-weight: 800;
}


.profile-info {
    text-align: left;
}


.profile-name {

    display: block;

    max-width: 170px;

    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;

    color: #334155;

    font-size: 12px;

    font-weight: 700;
}


.profile-role {

    display: block;

    margin-top: 3px;

    color: #94A3B8;

    font-size: 10px;
}


.profile-arrow {

    color: #64748B;

    font-size: 14px;

    transition: 0.2s;
}


.profile-btn.active .profile-arrow {
    transform: rotate(180deg);
}


/* =====================================================
   PROFILE DROPDOWN
===================================================== */

.profile-menu {

    position: absolute;

    right: 0;

    top: 55px;

    width: 245px;

    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 12px;

    box-shadow:
        0 18px 45px
        rgba(15,23,42,0.14);

    padding: 8px;

    opacity: 0;

    visibility: hidden;

    transform: translateY(-6px);

    transition: 0.2s;
}


.profile-menu.show {

    opacity: 1;

    visibility: visible;

    transform: translateY(0);
}


.profile-menu-header {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 11px;

    margin-bottom: 5px;

    background: #F8FAFC;

    border-radius: 8px;
}


.profile-big-avatar {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #1D4ED8,
            #06B6D4
        );

    color: white;

    font-size: 11px;

    font-weight: 800;
}


.profile-menu-header strong {

    display: block;

    max-width: 155px;

    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;

    color: #334155;

    font-size: 12px;
}


.profile-menu-header small {

    display: block;

    margin-top: 3px;

    color: #94A3B8;

    font-size: 9px;
}


.profile-menu a {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px;

    border-radius: 7px;

    color: #475569;

    font-size: 12px;

    font-weight: 600;

    transition: 0.15s;
}


.profile-menu a:hover {

    background: #EFF6FF;

    color: #1D4ED8;
}


.menu-icon {

    width: 22px;

    text-align: center;

    color: #64748B;

    font-size: 13px;
}


.profile-menu .logout-link {

    margin-top: 4px;

    border-top:
        1px solid #F1F5F9;

    padding-top: 12px;

    color: #DC2626;
}


/* =====================================================
   MAIN
===================================================== */

.main {

    width: 92%;

    max-width: 1250px;

    margin: auto;

    padding: 38px 0 55px;
}


/* =====================================================
   WELCOME
===================================================== */

.welcome {

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    gap: 20px;

    margin-bottom: 28px;
}


.welcome-label {

    display: inline-block;

    color: #2563EB;

    background: #EFF6FF;

    border:
        1px solid #DBEAFE;

    border-radius: 20px;

    padding: 6px 10px;

    font-size: 9px;

    font-weight: 800;

    letter-spacing: 0.5px;

    text-transform: uppercase;

    margin-bottom: 10px;
}


.welcome h1 {

    margin: 0;

    color: #0F172A;

    font-size: 30px;

    letter-spacing: -0.5px;

    line-height: 1.2;
}


.welcome p {

    margin: 8px 0 0;

    color: #64748B;

    font-size: 13px;
}


.date-box {

    color: #64748B;

    font-size: 11px;

    background: white;

    border:
        1px solid #E2E8F0;

    padding: 10px 14px;

    border-radius: 8px;
}


/* =====================================================
   STAT CARDS
===================================================== */

.stats {

    display: grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap: 14px;

    margin-bottom: 25px;
}


.stat-card {

    position: relative;

    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 12px;

    padding: 18px;

    overflow: hidden;

    transition: 0.2s;
}


.stat-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 10px 25px
        rgba(15,23,42,0.07);
}


.stat-card::before {

    content: "";

    position: absolute;

    left: 0;

    top: 0;

    width: 3px;

    height: 100%;
}


.stat-total::before {
    background: #2563EB;
}

.stat-pending::before {
    background: #F59E0B;
}

.stat-progress::before {
    background: #06B6D4;
}

.stat-resolved::before {
    background: #16A34A;
}

.stat-rejected::before {
    background: #DC2626;
}


.stat-top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 14px;
}


.stat-icon {

    width: 34px;

    height: 34px;

    border-radius: 8px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 12px;

    font-weight: 800;
}


.total-icon {

    color: #2563EB;

    background: #EFF6FF;
}


.pending-icon {

    color: #B45309;

    background: #FEF3C7;
}


.progress-icon {

    color: #0891B2;

    background: #ECFEFF;
}


.resolved-icon {

    color: #15803D;

    background: #DCFCE7;
}


.rejected-icon {

    color: #B91C1C;

    background: #FEE2E2;
}


.stat-label {

    color: #64748B;

    font-size: 10px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: 0.4px;
}


.stat-number {

    display: block;

    color: #0F172A;

    font-size: 27px;

    font-weight: 750;

    line-height: 1;
}


/* =====================================================
   CONTENT GRID
===================================================== */

.content-grid {

    display: grid;

    grid-template-columns:
        1.65fr 1fr;

    gap: 19px;

    margin-bottom: 23px;
}


.panel {

    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 13px;

    padding: 21px;

    box-shadow:
        0 3px 12px
        rgba(15,23,42,0.025);
}


.panel-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 20px;
}


.panel-title strong {

    display: block;

    color: #0F172A;

    font-size: 15px;
}


.panel-title span {

    display: block;

    margin-top: 5px;

    color: #94A3B8;

    font-size: 10px;
}


.view-all {

    color: #2563EB;

    font-size: 10px;

    font-weight: 700;

    padding: 7px 9px;

    border-radius: 6px;
}


.view-all:hover {

    background: #EFF6FF;
}


/* =====================================================
   STATUS OVERVIEW
===================================================== */

.status-item {

    margin-bottom: 18px;
}


.status-item:last-child {

    margin-bottom: 0;
}


.status-info {

    display: flex;

    justify-content: space-between;

    margin-bottom: 7px;
}


.status-name {

    display: flex;

    align-items: center;

    gap: 8px;

    color: #475569;

    font-size: 11px;

    font-weight: 600;
}


.status-dot {

    width: 8px;

    height: 8px;

    border-radius: 50%;
}


.dot-pending {
    background: #F59E0B;
}


.dot-progress {
    background: #06B6D4;
}


.dot-resolved {
    background: #16A34A;
}


.dot-rejected {
    background: #DC2626;
}


.status-value {

    color: #334155;

    font-size: 10px;

    font-weight: 700;
}


.progress-track {

    width: 100%;

    height: 7px;

    background: #F1F5F9;

    border-radius: 10px;

    overflow: hidden;
}


.progress-fill {

    height: 100%;

    border-radius: 10px;
}


.fill-pending {
    background: #F59E0B;
}


.fill-progress {
    background: #06B6D4;
}


.fill-resolved {
    background: #16A34A;
}


.fill-rejected {
    background: #DC2626;
}


/* =====================================================
   QUICK ACTIONS
===================================================== */

.quick-actions {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 10px;

    margin-top: 20px;

    padding-top: 19px;

    border-top:
        1px solid #F1F5F9;
}


.action {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 11px;

    border-radius: 8px;

    border:
        1px solid #E2E8F0;

    color: #475569;

    transition: 0.2s;
}


.action:hover {

    border-color: #BFDBFE;

    background: #EFF6FF;

    color: #1D4ED8;

    transform: translateY(-1px);
}


.action-icon {

    width: 31px;

    height: 31px;

    border-radius: 7px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #EFF6FF;

    color: #2563EB;

    font-size: 13px;

    font-weight: 800;
}


.action strong {

    display: block;

    font-size: 10px;
}


.action span {

    display: block;

    margin-top: 3px;

    font-size: 8px;

    color: #94A3B8;
}


/* =====================================================
   RECENT COMPLAINTS
===================================================== */

.recent-panel {

    background: white;

    border:
        1px solid #E2E8F0;

    border-radius: 13px;

    padding: 21px;

    overflow: hidden;
}


.table-wrap {
    overflow-x: auto;
}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 680px;
}


th {

    text-align: left;

    padding: 12px 10px;

    background: #F8FAFC;

    color: #64748B;

    font-size: 9px;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: 0.4px;
}


td {

    padding: 14px 10px;

    border-top:
        1px solid #F1F5F9;

    color: #475569;

    font-size: 10px;

    vertical-align: middle;
}


.complaint-id {

    color: #94A3B8;

    font-size: 9px;

    font-weight: 700;
}


.complaint-title {

    color: #334155;

    font-weight: 700;

    font-size: 11px;
}


.category {

    display: inline-block;

    padding: 5px 8px;

    background: #F1F5F9;

    border-radius: 5px;

    color: #64748B;

    font-size: 8px;

    font-weight: 700;
}


.priority {

    font-size: 9px;

    font-weight: 700;
}


.priority-high {
    color: #DC2626;
}


.priority-medium {
    color: #D97706;
}


.priority-low {
    color: #16A34A;
}


.status-badge {

    display: inline-block;

    padding: 6px 9px;

    border-radius: 15px;

    font-size: 8px;

    font-weight: 800;

    white-space: nowrap;
}


.badge-pending {

    color: #B45309;

    background: #FEF3C7;
}


.badge-progress {

    color: #0369A1;

    background: #E0F2FE;
}


.badge-resolved {

    color: #15803D;

    background: #DCFCE7;
}


.badge-rejected {

    color: #B91C1C;

    background: #FEE2E2;
}


.empty-state {

    text-align: center;

    padding: 48px 15px;

    color: #94A3B8;

    font-size: 12px;

    line-height: 1.7;
}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    text-align: center;

    color: #94A3B8;

    font-size: 10px;

    padding-top: 5px;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 1100px) {

    .stats {

        grid-template-columns:
            repeat(3, 1fr);
    }


    .content-grid {

        grid-template-columns: 1fr;
    }
}


@media (max-width: 700px) {

    .topbar {

        padding: 0 4%;
    }


    .brand-text span {

        display: none;
    }


    .profile-info {

        display: none;
    }


    .profile-arrow {

        display: none;
    }


    .main {

        width: 92%;

        padding-top: 27px;
    }


    .welcome {

        align-items: flex-start;

        flex-direction: column;
    }


    .welcome h1 {

        font-size: 25px;
    }


    .date-box {

        display: none;
    }


    .stats {

        grid-template-columns:
            repeat(2, 1fr);
    }


    .stat-card {

        padding: 15px;
    }


    .quick-actions {

        grid-template-columns: 1fr;
    }
}


@media (max-width: 430px) {

    .welcome h1 {

        font-size: 23px;
    }


    .stats {

        gap: 9px;
    }


    .stat-number {

        font-size: 23px;
    }


    .panel,
    .recent-panel {

        padding: 16px;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     TOPBAR
===================================================== -->

<header class="topbar">


    <div class="brand">

        <div class="brand-logo">
            OCS
        </div>


        <div class="brand-text">

            <strong>
                Office Complaint System
            </strong>

            <span>
                Employee Portal
            </span>

        </div>

    </div>


    <!-- PROFILE -->

    <div class="profile-area">


        <button
            class="profile-btn"
            id="profileButton"
            onclick="toggleProfileMenu()"
        >

            <div class="profile-avatar">

                <?php
                echo htmlspecialchars($initials);
                ?>

            </div>


            <div class="profile-info">

                <span class="profile-name">

                    <?php
                    echo htmlspecialchars($user_name);
                    ?>

                </span>


                <span class="profile-role">
                    Employee
                </span>

            </div>


            <span class="profile-arrow">
                ▾
            </span>

        </button>


        <div
            class="profile-menu"
            id="profileMenu"
        >


            <div class="profile-menu-header">


                <div class="profile-big-avatar">

                    <?php
                    echo htmlspecialchars($initials);
                    ?>

                </div>


                <div>

                    <strong>

                        <?php
                        echo htmlspecialchars($user_name);
                        ?>

                    </strong>


                    <small>
                        Employee Account
                    </small>

                </div>


            </div>


            <a href="dashboard.php">

                <span class="menu-icon">
                    ▦
                </span>

                Dashboard

            </a>


            <a href="profile.php">

                <span class="menu-icon">
                    ◉
                </span>

                My Profile

            </a>


            <a href="my_complaints.php">

                <span class="menu-icon">
                    ☰
                </span>

                My Complaints

            </a>


            <a
                href="logout.php"
                class="logout-link"
            >

                <span class="menu-icon">
                    ↪
                </span>

                Logout

            </a>


        </div>


    </div>


</header>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main">


    <!-- WELCOME -->

    <section class="welcome">


        <div>

            <div class="welcome-label">
                Employee Dashboard
            </div>


            <h1>

                Welcome back,
                <?php
                echo htmlspecialchars($user_name);
                ?>.

            </h1>


            <p>
                Here's an overview of your workplace complaints.
            </p>

        </div>


        <div class="date-box">

            <?php
            echo date("l, d M Y");
            ?>

        </div>


    </section>


    <!-- =================================================
         STATISTICS
    ================================================== -->

    <section class="stats">


        <div class="stat-card stat-total">

            <div class="stat-top">

                <span class="stat-label">
                    Total Complaints
                </span>

                <div class="stat-icon total-icon">
                    T
                </div>

            </div>


            <span class="stat-number">
                <?php echo $total; ?>
            </span>

        </div>


        <div class="stat-card stat-pending">

            <div class="stat-top">

                <span class="stat-label">
                    Pending
                </span>

                <div class="stat-icon pending-icon">
                    P
                </div>

            </div>


            <span class="stat-number">
                <?php echo $pending; ?>
            </span>

        </div>


        <div class="stat-card stat-progress">

            <div class="stat-top">

                <span class="stat-label">
                    In Progress
                </span>

                <div class="stat-icon progress-icon">
                    I
                </div>

            </div>


            <span class="stat-number">
                <?php echo $in_progress; ?>
            </span>

        </div>


        <div class="stat-card stat-resolved">

            <div class="stat-top">

                <span class="stat-label">
                    Resolved
                </span>

                <div class="stat-icon resolved-icon">
                    R
                </div>

            </div>


            <span class="stat-number">
                <?php echo $resolved; ?>
            </span>

        </div>


        <div class="stat-card stat-rejected">

            <div class="stat-top">

                <span class="stat-label">
                    Rejected
                </span>

                <div class="stat-icon rejected-icon">
                    X
                </div>

            </div>


            <span class="stat-number">
                <?php echo $rejected; ?>
            </span>

        </div>


    </section>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <section class="content-grid">


        <!-- STATUS -->

        <div class="panel">


            <div class="panel-header">

                <div class="panel-title">

                    <strong>
                        Complaint Status Overview
                    </strong>

                    <span>
                        Current distribution of your complaints
                    </span>

                </div>

            </div>


            <!-- PENDING -->

            <div class="status-item">

                <div class="status-info">

                    <div class="status-name">

                        <span
                            class="status-dot dot-pending"
                        ></span>

                        Pending

                    </div>


                    <span class="status-value">

                        <?php echo $pending; ?>

                        (

                        <?php echo $pending_percent; ?>%

                        )

                    </span>

                </div>


                <div class="progress-track">

                    <div
                        class="progress-fill fill-pending"
                        style="
                            width:
                            <?php echo $pending_percent; ?>%;
                        "
                    ></div>

                </div>

            </div>


            <!-- IN PROGRESS -->

            <div class="status-item">

                <div class="status-info">

                    <div class="status-name">

                        <span
                            class="status-dot dot-progress"
                        ></span>

                        In Progress

                    </div>


                    <span class="status-value">

                        <?php echo $in_progress; ?>

                        (

                        <?php echo $progress_percent; ?>%

                        )

                    </span>

                </div>


                <div class="progress-track">

                    <div
                        class="progress-fill fill-progress"
                        style="
                            width:
                            <?php echo $progress_percent; ?>%;
                        "
                    ></div>

                </div>

            </div>


            <!-- RESOLVED -->

            <div class="status-item">

                <div class="status-info">

                    <div class="status-name">

                        <span
                            class="status-dot dot-resolved"
                        ></span>

                        Resolved

                    </div>


                    <span class="status-value">

                        <?php echo $resolved; ?>

                        (

                        <?php echo $resolved_percent; ?>%

                        )

                    </span>

                </div>


                <div class="progress-track">

                    <div
                        class="progress-fill fill-resolved"
                        style="
                            width:
                            <?php echo $resolved_percent; ?>%;
                        "
                    ></div>

                </div>

            </div>


            <!-- REJECTED -->

            <div class="status-item">

                <div class="status-info">

                    <div class="status-name">

                        <span
                            class="status-dot dot-rejected"
                        ></span>

                        Rejected

                    </div>


                    <span class="status-value">

                        <?php echo $rejected; ?>

                        (

                        <?php echo $rejected_percent; ?>%

                        )

                    </span>

                </div>


                <div class="progress-track">

                    <div
                        class="progress-fill fill-rejected"
                        style="
                            width:
                            <?php echo $rejected_percent; ?>%;
                        "
                    ></div>

                </div>

            </div>


        </div>


        <!-- QUICK ACTIONS -->

        <div class="panel">


            <div class="panel-header">

                <div class="panel-title">

                    <strong>
                        Quick Actions
                    </strong>

                    <span>
                        Frequently used options
                    </span>

                </div>

            </div>


            <div class="quick-actions">


                <a
                    href="submit_complaint.php"
                    class="action"
                >

                    <div class="action-icon">
                        +
                    </div>

                    <div>

                        <strong>
                            New Complaint
                        </strong>

                        <span>
                            Report an issue
                        </span>

                    </div>

                </a>


                <a
                    href="my_complaints.php"
                    class="action"
                >

                    <div class="action-icon">
                        #
                    </div>

                    <div>

                        <strong>
                            My Complaints
                        </strong>

                        <span>
                            View submissions
                        </span>

                    </div>

                </a>


                <a
                    href="profile.php"
                    class="action"
                >

                    <div class="action-icon">
                        ◉
                    </div>

                    <div>

                        <strong>
                            My Profile
                        </strong>

                        <span>
                            Account information
                        </span>

                    </div>

                </a>


                <a
                    href="logout.php"
                    class="action"
                >

                    <div class="action-icon">
                        ↪
                    </div>

                    <div>

                        <strong>
                            Sign Out
                        </strong>

                        <span>
                            End your session
                        </span>

                    </div>

                </a>


            </div>


        </div>


    </section>


    <!-- =================================================
         RECENT COMPLAINTS
    ================================================== -->

    <section class="recent-panel">


        <div class="panel-header">


            <div class="panel-title">

                <strong>
                    Recent Complaints
                </strong>

                <span>
                    Your latest submitted workplace issues
                </span>

            </div>


            <a
                href="my_complaints.php"
                class="view-all"
            >
                View All
            </a>


        </div>


        <div class="table-wrap">


        <?php

        if (
            $recent_result &&
            mysqli_num_rows($recent_result) > 0
        ):

        ?>


        <table>


            <thead>

                <tr>

                    <th>ID</th>

                    <th>Complaint</th>

                    <th>Category</th>

                    <th>Priority</th>

                    <th>Status</th>

                    <th>Submitted</th>

                </tr>

            </thead>


            <tbody>


            <?php

            while (
                $row =
                mysqli_fetch_assoc($recent_result)
            ):


                $status_class = "";

                if (
                    $row['status'] == "Pending"
                ) {

                    $status_class =
                        "badge-pending";

                } elseif (
                    $row['status'] == "In Progress"
                ) {

                    $status_class =
                        "badge-progress";

                } elseif (
                    $row['status'] == "Resolved"
                ) {

                    $status_class =
                        "badge-resolved";

                } elseif (
                    $row['status'] == "Rejected"
                ) {

                    $status_class =
                        "badge-rejected";
                }


                $priority_class = "";

                if (
                    $row['priority'] == "High"
                ) {

                    $priority_class =
                        "priority-high";

                } elseif (
                    $row['priority'] == "Medium"
                ) {

                    $priority_class =
                        "priority-medium";

                } else {

                    $priority_class =
                        "priority-low";
                }

            ?>


            <tr>


                <td>

                    <span class="complaint-id">

                        #

                        <?php
                        echo htmlspecialchars(
                            $row['id']
                        );
                        ?>

                    </span>

                </td>


                <td>

                    <span class="complaint-title">

                        <?php
                        echo htmlspecialchars(
                            $row['title']
                        );
                        ?>

                    </span>

                </td>


                <td>

                    <span class="category">

                        <?php
                        echo htmlspecialchars(
                            $row['category']
                        );
                        ?>

                    </span>

                </td>


                <td>

                    <span
                        class="
                            priority
                            <?php
                            echo $priority_class;
                            ?>
                        "
                    >

                        <?php
                        echo htmlspecialchars(
                            $row['priority']
                        );
                        ?>

                    </span>

                </td>


                <td>

                    <span
                        class="
                            status-badge
                            <?php
                            echo $status_class;
                            ?>
                        "
                    >

                        <?php
                        echo htmlspecialchars(
                            $row['status']
                        );
                        ?>

                    </span>

                </td>


                <td>

                    <?php

                    echo date(
                        "d M Y",
                        strtotime(
                            $row['created_at']
                        )
                    );

                    ?>

                </td>


            </tr>


            <?php

            endwhile;

            ?>


            </tbody>


        </table>


        <?php

        else:

        ?>


        <div class="empty-state">

            You haven't submitted any complaints yet.

            <br><br>

            <a
                href="submit_complaint.php"
                style="
                    color:#2563EB;
                    font-weight:700;
                "
            >
                Submit your first complaint →
            </a>

        </div>


        <?php

        endif;

        ?>


        </div>


    </section>


    <div class="footer">

        Office Complaint System
        &nbsp;•&nbsp;
        Employee Portal
        &nbsp;•&nbsp;
        © 2026

    </div>


</main>


<script>

function toggleProfileMenu() {

    const menu =
        document.getElementById(
            "profileMenu"
        );

    const button =
        document.getElementById(
            "profileButton"
        );

    menu.classList.toggle("show");

    button.classList.toggle("active");
}


document.addEventListener(
    "click",
    function(event) {

        const profileArea =
            document.querySelector(
                ".profile-area"
            );

        const menu =
            document.getElementById(
                "profileMenu"
            );

        const button =
            document.getElementById(
                "profileButton"
            );


        if (
            !profileArea.contains(
                event.target
            )
        ) {

            menu.classList.remove(
                "show"
            );

            button.classList.remove(
                "active"
            );
        }

    }
);

</script>


</body>

</html>
