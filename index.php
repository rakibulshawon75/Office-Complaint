
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Office Complaint System</title>

    <link rel="stylesheet" href="style.css">


    <style>

        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            margin: 0;

            font-family: Arial, Helvetica, sans-serif;

            color: #0F172A;

            background: #F8FAFC;
        }


        a {
            text-decoration: none;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .main-nav {

            height: 76px;

            padding: 0 6%;

            display: flex;

            align-items: center;

            justify-content: space-between;

            background: rgba(255,255,255,0.96);

            border-bottom: 1px solid #E2E8F0;

            position: relative;

            z-index: 100;
        }


        .brand-area {

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

            font-size: 17px;

            font-weight: 800;

            box-shadow:
                0 7px 18px rgba(37,99,235,0.22);
        }


        .brand-name {

            font-size: 19px;

            font-weight: 700;

            color: #0F172A;

            line-height: 1.1;
        }


        .brand-subtitle {

            display: block;

            margin-top: 3px;

            color: #64748B;

            font-size: 10px;

            font-weight: 500;
        }


        .nav-right {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .nav-login {

            color: #1E40AF;

            padding: 9px 15px;

            font-size: 13px;

            font-weight: 600;

            border-radius: 8px;

            transition: 0.2s;
        }


        .nav-login:hover {

            background: #EFF6FF;

            color: #1E3A8A;
        }


        .nav-admin {

            color: white;

            padding: 9px 16px;

            font-size: 13px;

            font-weight: 600;

            border-radius: 8px;

            background: #0F172A;

            transition: 0.2s;
        }


        .nav-admin:hover {

            color: white;

            background: #1E293B;

            transform: translateY(-1px);
        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(
                    120deg,
                    #0F172A 0%,
                    #172554 48%,
                    #1D4ED8 100%
                );

            min-height: 610px;

            color: white;
        }


        .hero::before {

            content: "";

            position: absolute;

            width: 500px;

            height: 500px;

            border-radius: 50%;

            background: rgba(6,182,212,0.10);

            top: -250px;

            right: -100px;
        }


        .hero::after {

            content: "";

            position: absolute;

            width: 350px;

            height: 350px;

            border-radius: 50%;

            background: rgba(59,130,246,0.10);

            bottom: -220px;

            left: -100px;
        }


        .hero-inner {

            width: 90%;

            max-width: 1180px;

            min-height: 610px;

            margin: auto;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 65px;

            position: relative;

            z-index: 2;

            padding: 65px 0;
        }


        .hero-content {

            flex: 1;

            max-width: 630px;
        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 8px 13px;

            border-radius: 30px;

            background: rgba(255,255,255,0.08);

            border: 1px solid rgba(255,255,255,0.14);

            color: #BAE6FD;

            font-size: 12px;

            font-weight: 600;

            margin-bottom: 20px;

            backdrop-filter: blur(8px);
        }


        .badge-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #22D3EE;

            box-shadow:
                0 0 10px rgba(34,211,238,0.8);
        }


        .hero h1 {

            margin: 0 0 20px;

            font-size: 56px;

            line-height: 1.08;

            letter-spacing: -1.8px;

            font-weight: 750;
        }


        .hero h1 span {

            color: #60A5FA;
        }


        .hero-description {

            max-width: 580px;

            color: #CBD5E1;

            font-size: 16px;

            line-height: 1.75;

            margin: 0 0 31px;
        }


        .hero-actions {

            display: flex;

            gap: 12px;

            flex-wrap: wrap;
        }


        .hero-primary {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 13px 21px;

            border-radius: 9px;

            background: white;

            color: #1D4ED8;

            font-size: 14px;

            font-weight: 700;

            box-shadow:
                0 10px 25px rgba(0,0,0,0.18);

            transition: 0.2s;
        }


        .hero-primary:hover {

            color: #1E3A8A;

            transform: translateY(-2px);
        }


        .hero-secondary {

            display: inline-flex;

            align-items: center;

            padding: 13px 21px;

            border-radius: 9px;

            background: rgba(255,255,255,0.08);

            border: 1px solid rgba(255,255,255,0.22);

            color: white;

            font-size: 14px;

            font-weight: 600;

            transition: 0.2s;
        }


        .hero-secondary:hover {

            color: white;

            background: rgba(255,255,255,0.14);

            transform: translateY(-2px);
        }


        /* =====================================================
           DASHBOARD PREVIEW
        ===================================================== */

        .preview-wrapper {

            width: 450px;

            flex-shrink: 0;

            position: relative;
        }


        .preview-glow {

            position: absolute;

            width: 250px;

            height: 250px;

            border-radius: 50%;

            background: rgba(34,211,238,0.14);

            filter: blur(30px);

            right: 20px;

            top: 35px;
        }


        .dashboard-preview {

            position: relative;

            background: #FFFFFF;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 30px 70px rgba(0,0,0,0.32);

            border: 1px solid rgba(255,255,255,0.3);
        }


        .preview-top {

            height: 45px;

            display: flex;

            align-items: center;

            padding: 0 15px;

            background: #F8FAFC;

            border-bottom: 1px solid #E2E8F0;
        }


        .preview-dots {

            display: flex;

            gap: 5px;
        }


        .preview-dots span {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #CBD5E1;
        }


        .preview-title {

            margin-left: auto;

            margin-right: auto;

            color: #64748B;

            font-size: 10px;

            font-weight: 600;
        }


        .preview-body {

            padding: 18px;
        }


        .preview-heading {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 15px;
        }


        .preview-heading strong {

            color: #0F172A;

            font-size: 16px;
        }


        .preview-heading span {

            color: #64748B;

            font-size: 10px;
        }


        .stats-row {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 8px;

            margin-bottom: 15px;
        }


        .stat-box {

            padding: 11px 8px;

            border-radius: 8px;

            background: #F8FAFC;

            border: 1px solid #E2E8F0;
        }


        .stat-box strong {

            display: block;

            color: #0F172A;

            font-size: 17px;

            margin-bottom: 3px;
        }


        .stat-box span {

            color: #64748B;

            font-size: 8px;

            font-weight: 600;
        }


        .stat-blue {

            border-left: 3px solid #2563EB;
        }


        .stat-yellow {

            border-left: 3px solid #F59E0B;
        }


        .stat-cyan {

            border-left: 3px solid #06B6D4;
        }


        .stat-green {

            border-left: 3px solid #16A34A;
        }


        .preview-table {

            border: 1px solid #E2E8F0;

            border-radius: 9px;

            overflow: hidden;
        }


        .preview-table-header {

            display: grid;

            grid-template-columns:
                1.5fr 1fr 0.8fr;

            padding: 9px 10px;

            background: #F8FAFC;

            color: #64748B;

            font-size: 8px;

            font-weight: 700;

            text-transform: uppercase;
        }


        .preview-row {

            display: grid;

            grid-template-columns:
                1.5fr 1fr 0.8fr;

            padding: 11px 10px;

            border-top: 1px solid #F1F5F9;

            align-items: center;
        }


        .preview-row strong {

            color: #334155;

            font-size: 9px;

            font-weight: 600;
        }


        .preview-row span {

            color: #64748B;

            font-size: 8px;
        }


        .status {

            display: inline-block;

            width: fit-content;

            padding: 4px 7px;

            border-radius: 12px;

            font-size: 7px !important;

            font-weight: 700;
        }


        .status-resolved {

            background: #DCFCE7;

            color: #15803D !important;
        }


        .status-progress {

            background: #DBEAFE;

            color: #1D4ED8 !important;
        }


        .status-pending {

            background: #FEF3C7;

            color: #B45309 !important;
        }


        /* =====================================================
           TRUST STRIP
        ===================================================== */

        .trust-strip {

            width: 90%;

            max-width: 1080px;

            margin: -25px auto 0;

            position: relative;

            z-index: 5;

            background: white;

            border: 1px solid #E2E8F0;

            border-radius: 13px;

            box-shadow:
                0 10px 30px rgba(15,23,42,0.08);

            padding: 17px 22px;

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }


        .trust-item {

            display: flex;

            align-items: center;

            gap: 11px;
        }


        .trust-icon {

            width: 35px;

            height: 35px;

            border-radius: 8px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #EFF6FF;

            color: #2563EB;

            font-weight: 700;

            font-size: 13px;
        }


        .trust-item strong {

            display: block;

            color: #334155;

            font-size: 12px;

            margin-bottom: 2px;
        }


        .trust-item span {

            color: #94A3B8;

            font-size: 9px;
        }


        /* =====================================================
           FEATURES SECTION
        ===================================================== */

        .features-section {

            width: 90%;

            max-width: 1100px;

            margin: 70px auto 65px;

            text-align: center;
        }


        .section-label {

            display: inline-block;

            color: #2563EB;

            background: #EFF6FF;

            border: 1px solid #DBEAFE;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.6px;

            margin-bottom: 11px;
        }


        .features-section h2 {

            margin: 0 0 10px;

            color: #0F172A;

            font-size: 29px;
        }


        .section-description {

            max-width: 570px;

            margin: 0 auto 30px;

            color: #64748B;

            font-size: 13px;

            line-height: 1.6;
        }


        .features-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 17px;

            text-align: left;
        }


        .feature-card {

            background: white;

            border: 1px solid #E2E8F0;

            border-radius: 14px;

            padding: 22px;

            transition: 0.2s;
        }


        .feature-card:hover {

            transform: translateY(-4px);

            border-color: #BFDBFE;

            box-shadow:
                0 13px 30px rgba(15,23,42,0.07);
        }


        .feature-number {

            color: #CBD5E1;

            font-size: 11px;

            font-weight: 700;

            margin-bottom: 14px;
        }


        .feature-icon {

            width: 43px;

            height: 43px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #EFF6FF;

            color: #2563EB;

            font-weight: 800;

            font-size: 17px;

            margin-bottom: 14px;
        }


        .feature-card h3 {

            margin: 0 0 8px;

            color: #0F172A;

            font-size: 16px;
        }


        .feature-card p {

            margin: 0;

            color: #64748B;

            font-size: 12px;

            line-height: 1.6;
        }


        /* =====================================================
           CTA
        ===================================================== */

        .cta {

            width: 90%;

            max-width: 1100px;

            margin: 0 auto 60px;

            border-radius: 17px;

            padding: 35px 40px;

            background:
                linear-gradient(
                    120deg,
                    #0F172A,
                    #1E3A8A,
                    #2563EB
                );

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 25px;

            color: white;

            overflow: hidden;

            position: relative;
        }


        .cta::after {

            content: "";

            position: absolute;

            width: 230px;

            height: 230px;

            border-radius: 50%;

            background: rgba(6,182,212,0.12);

            right: -60px;

            top: -110px;
        }


        .cta-content {

            position: relative;

            z-index: 2;
        }


        .cta h2 {

            margin: 0 0 7px;

            font-size: 22px;
        }


        .cta p {

            margin: 0;

            color: #CBD5E1;

            font-size: 12px;
        }


        .cta-button {

            position: relative;

            z-index: 2;

            background: white;

            color: #1D4ED8;

            padding: 11px 17px;

            border-radius: 8px;

            font-size: 12px;

            font-weight: 700;

            white-space: nowrap;
        }


        .cta-button:hover {

            color: #1E3A8A;

            background: #EFF6FF;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {

            border-top: 1px solid #E2E8F0;

            background: white;

            padding: 23px 6%;

            display: flex;

            justify-content: space-between;

            align-items: center;

            color: #94A3B8;

            font-size: 11px;
        }


        .footer strong {

            color: #475569;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 950px) {

            .hero-inner {

                flex-direction: column;

                align-items: flex-start;

                padding-top: 55px;

                padding-bottom: 65px;
            }


            .hero-content {

                max-width: 700px;
            }


            .preview-wrapper {

                width: 100%;

                max-width: 480px;

                align-self: center;
            }


            .hero h1 {

                font-size: 48px;
            }


            .features-grid {

                grid-template-columns: 1fr;

            }
        }


        @media (max-width: 700px) {

            .main-nav {

                height: auto;

                padding: 14px 5%;

                gap: 10px;
            }


            .brand-name {

                font-size: 16px;
            }


            .brand-subtitle {

                display: none;
            }


            .brand-logo {

                width: 35px;

                height: 35px;
            }


            .nav-login {

                display: none;
            }


            .nav-admin {

                font-size: 11px;

                padding: 8px 11px;
            }


            .hero-inner {

                width: 90%;

                min-height: auto;

                padding: 48px 0 65px;
            }


            .hero h1 {

                font-size: 38px;

                letter-spacing: -1px;
            }


            .hero-description {

                font-size: 14px;
            }


            .hero-actions {

                flex-direction: column;
            }


            .hero-primary,
            .hero-secondary {

                width: 100%;

                justify-content: center;
            }


            .preview-wrapper {

                width: 100%;
            }


            .stats-row {

                grid-template-columns:
                    repeat(2, 1fr);
            }


            .trust-strip {

                width: 88%;

                grid-template-columns: 1fr;

                gap: 12px;

                padding: 16px;
            }


            .features-section {

                margin-top: 55px;
            }


            .features-section h2 {

                font-size: 24px;
            }


            .cta {

                width: 90%;

                flex-direction: column;

                align-items: flex-start;

                padding: 28px;
            }


            .footer {

                flex-direction: column;

                gap: 7px;

                text-align: center;
            }
        }


        @media (max-width: 430px) {

            .hero h1 {

                font-size: 34px;
            }


            .preview-body {

                padding: 13px;
            }


            .preview-row strong {

                font-size: 8px;
            }


            .preview-table-header {

                font-size: 7px;
            }
        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="main-nav">


    <div class="brand-area">

        <div class="brand-logo">
            OCS
        </div>

        <div>

            <div class="brand-name">
                Office Complaint System
            </div>

            <span class="brand-subtitle">
                Workplace Issue Management
            </span>

        </div>

    </div>


    <div class="nav-right">

        <a
            href="login.php"
            class="nav-login"
        >
            Employee Login
        </a>


        <a
            href="admin_login.php"
            class="nav-admin"
        >
            Admin Portal
        </a>

    </div>

</nav>


<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">


    <div class="hero-inner">


        <div class="hero-content">


            <div class="hero-badge">

                <span class="badge-dot"></span>

                Employee Complaint Management

            </div>


            <h1>

                Report Issues.<br>

                <span>Track Progress.</span>

            </h1>


            <p class="hero-description">

                A centralized platform for employees to
                submit workplace complaints, monitor their
                progress and stay informed until every issue
                reaches a resolution.

            </p>


            <div class="hero-actions">


                <a
                    href="login.php"
                    class="hero-primary"
                >
                    Employee Login
                    <span>→</span>
                </a>


                <a
                    href="register.php"
                    class="hero-secondary"
                >
                    Create Employee Account
                </a>

            </div>


        </div>


        <!-- =================================================
             DASHBOARD PREVIEW
        ================================================== -->

        <div class="preview-wrapper">


            <div class="preview-glow"></div>


            <div class="dashboard-preview">


                <div class="preview-top">

                    <div class="preview-dots">

                        <span></span>
                        <span></span>
                        <span></span>

                    </div>


                    <div class="preview-title">
                        ADMIN DASHBOARD
                    </div>

                </div>


                <div class="preview-body">


                    <div class="preview-heading">

                        <strong>
                            Complaint Overview
                        </strong>

                        <span>
                            Current Status
                        </span>

                    </div>


                    <!-- STATS -->

                    <div class="stats-row">


                        <div class="stat-box stat-blue">

                            <strong>24</strong>

                            <span>
                                TOTAL
                            </span>

                        </div>


                        <div class="stat-box stat-yellow">

                            <strong>06</strong>

                            <span>
                                PENDING
                            </span>

                        </div>


                        <div class="stat-box stat-cyan">

                            <strong>07</strong>

                            <span>
                                PROGRESS
                            </span>

                        </div>


                        <div class="stat-box stat-green">

                            <strong>11</strong>

                            <span>
                                RESOLVED
                            </span>

                        </div>


                    </div>


                    <!-- TABLE -->

                    <div class="preview-table">


                        <div class="preview-table-header">

                            <div>
                                Complaint
                            </div>

                            <div>
                                Category
                            </div>

                            <div>
                                Status
                            </div>

                        </div>


                        <div class="preview-row">

                            <div>
                                <strong>
                                    Internet Problem
                                </strong>
                            </div>

                            <div>
                                <span>
                                    IT
                                </span>
                            </div>

                            <div>

                                <span class="status status-resolved">
                                    Resolved
                                </span>

                            </div>

                        </div>


                        <div class="preview-row">

                            <div>
                                <strong>
                                    Office Equipment
                                </strong>
                            </div>

                            <div>
                                <span>
                                    Workplace
                                </span>
                            </div>

                            <div>

                                <span class="status status-progress">
                                    In Progress
                                </span>

                            </div>

                        </div>


                        <div class="preview-row">

                            <div>
                                <strong>
                                    System Access
                                </strong>
                            </div>

                            <div>
                                <span>
                                    IT
                                </span>
                            </div>

                            <div>

                                <span class="status status-pending">
                                    Pending
                                </span>

                            </div>

                        </div>


                    </div>


                </div>


            </div>


        </div>


    </div>


</section>


<!-- =====================================================
     TRUST STRIP
===================================================== -->

<div class="trust-strip">


    <div class="trust-item">

        <div class="trust-icon">
            01
        </div>

        <div>

            <strong>
                Simple Submission
            </strong>

            <span>
                Report issues in a few steps
            </span>

        </div>

    </div>


    <div class="trust-item">

        <div class="trust-icon">
            02
        </div>

        <div>

            <strong>
                Status Tracking
            </strong>

            <span>
                Follow every complaint
            </span>

        </div>

    </div>


    <div class="trust-item">

        <div class="trust-icon">
            03
        </div>

        <div>

            <strong>
                Organized Resolution
            </strong>

            <span>
                Structured complaint management
            </span>

        </div>

    </div>


</div>


<!-- =====================================================
     FEATURES
===================================================== -->

<section class="features-section">


    <div class="section-label">
        Platform Features
    </div>


    <h2>
        Everything in One Place
    </h2>


    <p class="section-description">

        The system keeps employee complaints organized
        and provides a clear workflow from submission
        to final resolution.

    </p>


    <div class="features-grid">


        <div class="feature-card">


            <div class="feature-number">
                FEATURE 01
            </div>


            <div class="feature-icon">
                +
            </div>


            <h3>
                Easy Complaint Submission
            </h3>


            <p>

                Employees can quickly submit complaints
                with category, description and priority
                information.

            </p>


        </div>


        <div class="feature-card">


            <div class="feature-number">
                FEATURE 02
            </div>


            <div class="feature-icon">
                #
            </div>


            <h3>
                Complaint Tracking
            </h3>


            <p>

                Monitor complaint status and view
                administrative responses from one place.

            </p>


        </div>


        <div class="feature-card">


            <div class="feature-number">
                FEATURE 03
            </div>


            <div class="feature-icon">
                ✓
            </div>


            <h3>
                Efficient Administration
            </h3>


            <p>

                Administrators can search, filter,
                update and manage complaints efficiently.

            </p>


        </div>


    </div>


</section>


<!-- =====================================================
     CTA
===================================================== -->

<section class="cta">


    <div class="cta-content">

        <h2>
            Ready to report a workplace issue?
        </h2>

        <p>
            Sign in to your employee account and submit
            your complaint securely.
        </p>

    </div>


    <a
        href="login.php"
        class="cta-button"
    >
        Get Started →
    </a>


</section>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">


    <div>

        <strong>
            Office Complaint System
        </strong>

        &nbsp; | &nbsp;

        Workplace Issue Management

    </div>


    <div>

        © 2026 All Rights Reserved

    </div>


</footer>


</body>

</html>
