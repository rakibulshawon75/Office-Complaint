```php
<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


/* =========================
   GET USER INFORMATION
========================= */

$sql = "SELECT name, email, department, created_at
        FROM users
        WHERE id='$user_id'";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);


/* =========================
   PROFILE INITIAL
========================= */

$name_parts = explode(" ", trim($user['name']));

if (count($name_parts) >= 2) {

    $initials =
        strtoupper(
            substr($name_parts[0], 0, 1) .
            substr($name_parts[count($name_parts) - 1], 0, 1)
        );

} else {

    $initials =
        strtoupper(
            substr($user['name'], 0, 2)
        );

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Profile - Office Complaint System</title>

    <link rel="stylesheet" href="style.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        body {
            min-height: 100vh;
            background: #F1F5FF;
            color: #1E293B;
        }


        /* ================= TOP BAR ================= */

        .topbar {
            background: linear-gradient(
                135deg,
                #1E3A8A,
                #2563EB
            );

            color: white;

            padding: 18px 6%;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow:
                0 4px 15px rgba(30,58,138,0.20);
        }


        .topbar h2 {
            color: white;
            margin: 0;
            font-size: 21px;
        }


        .back-btn {
            color: white;
            text-decoration: none;

            padding: 9px 15px;

            border-radius: 8px;

            background: rgba(255,255,255,0.12);

            font-size: 13px;
            font-weight: 600;

            transition: 0.2s;
        }


        .back-btn:hover {
            background: rgba(255,255,255,0.22);
            color: white;
        }


        /* ================= PROFILE CONTAINER ================= */

        .profile-container {
            width: 90%;
            max-width: 850px;

            margin: 45px auto;
        }


        /* ================= PROFILE CARD ================= */

        .profile-card {
            background: white;

            border-radius: 18px;

            box-shadow:
                0 8px 30px rgba(30,58,138,0.10);

            overflow: hidden;
        }


        /* ================= PROFILE HEADER ================= */

        .profile-header {
            background: linear-gradient(
                135deg,
                #1E3A8A,
                #2563EB
            );

            padding: 35px;

            display: flex;
            align-items: center;

            gap: 20px;

            color: white;
        }


        .large-avatar {
            width: 82px;
            height: 82px;

            border-radius: 50%;

            background: linear-gradient(
                135deg,
                #60A5FA,
                #BFDBFE
            );

            color: #1E3A8A;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;
            font-weight: 800;

            border: 4px solid rgba(255,255,255,0.35);

            box-shadow:
                0 5px 15px rgba(0,0,0,0.15);
        }


        .profile-header h1 {
            margin: 0 0 6px;

            font-size: 24px;

            color: white;
        }


        .profile-header p {
            margin: 0;

            color: #DBEAFE;

            font-size: 14px;
        }


        /* ================= PROFILE BODY ================= */

        .profile-body {
            padding: 30px;
        }


        .section-title {
            color: #1E3A8A;

            font-size: 18px;

            margin: 0 0 20px;

            padding-bottom: 12px;

            border-bottom: 1px solid #E2E8F0;
        }


        /* ================= INFORMATION GRID ================= */

        .info-grid {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;
        }


        .info-box {
            background: #F8FAFC;

            border: 1px solid #E2E8F0;

            border-radius: 11px;

            padding: 17px;
        }


        .info-label {
            display: block;

            color: #64748B;

            font-size: 12px;

            font-weight: 600;

            margin-bottom: 7px;
        }


        .info-value {
            color: #1E293B;

            font-size: 14px;

            font-weight: 600;

            word-break: break-word;
        }


        /* ================= ACTIONS ================= */

        .profile-actions {
            display: flex;

            gap: 12px;

            margin-top: 28px;

            padding-top: 22px;

            border-top: 1px solid #E2E8F0;
        }


        .btn {
            display: inline-block;

            padding: 10px 18px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            transition: 0.2s;
        }


        .btn-primary {
            background:
                linear-gradient(
                    135deg,
                    #1E3A8A,
                    #2563EB
                );

            color: white;
        }


        .btn-primary:hover {
            background: #1D4ED8;
            color: white;
        }


        .btn-secondary {
            background: #EFF6FF;

            color: #2563EB;
        }


        .btn-secondary:hover {
            background: #DBEAFE;
            color: #1D4ED8;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 600px) {

            .topbar {
                padding: 16px 5%;
            }


            .profile-container {
                width: 92%;
                margin: 25px auto;
            }


            .profile-header {
                padding: 25px;

                flex-direction: column;

                text-align: center;
            }


            .info-grid {
                grid-template-columns: 1fr;
            }


            .profile-actions {
                flex-direction: column;
            }


            .btn {
                text-align: center;
            }

        }

    </style>

</head>


<body>


<!-- ================= TOP BAR ================= -->

<div class="topbar">

    <h2>
        Office Complaint System
    </h2>


    <a
        href="dashboard.php"
        class="back-btn"
    >
        ← Dashboard
    </a>

</div>


<!-- ================= PROFILE ================= -->

<div class="profile-container">


    <div class="profile-card">


        <!-- PROFILE HEADER -->

        <div class="profile-header">


            <div class="large-avatar">

                <?php
                echo $initials;
                ?>

            </div>


            <div>

                <h1>

                    <?php
                    echo htmlspecialchars(
                        $user['name']
                    );
                    ?>

                </h1>


                <p>
                    Employee Account
                </p>

            </div>


        </div>


        <!-- PROFILE BODY -->

        <div class="profile-body">


            <h2 class="section-title">
                Personal Information
            </h2>


            <div class="info-grid">


                <!-- NAME -->

                <div class="info-box">

                    <span class="info-label">
                        Full Name
                    </span>

                    <div class="info-value">

                        <?php
                        echo htmlspecialchars(
                            $user['name']
                        );
                        ?>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="info-box">

                    <span class="info-label">
                        Email Address
                    </span>

                    <div class="info-value">

                        <?php
                        echo htmlspecialchars(
                            $user['email']
                        );
                        ?>

                    </div>

                </div>


                <!-- DEPARTMENT -->

                <div class="info-box">

                    <span class="info-label">
                        Department
                    </span>

                    <div class="info-value">

                        <?php

                        echo !empty(
                            $user['department']
                        )
                            ? htmlspecialchars(
                                $user['department']
                            )
                            : "Not specified";

                        ?>

                    </div>

                </div>


                <!-- ACCOUNT CREATED -->

                <div class="info-box">

                    <span class="info-label">
                        Account Created
                    </span>

                    <div class="info-value">

                        <?php

                        echo date(
                            "d M Y",
                            strtotime(
                                $user['created_at']
                            )
                        );

                        ?>

                    </div>

                </div>


            </div>


            <!-- ACTIONS -->

            <div class="profile-actions">


                <a
                    href="dashboard.php"
                    class="btn btn-primary"
                >
                    ← Back to Dashboard
                </a>


                <a
                    href="my_complaints.php"
                    class="btn btn-secondary"
                >
                    View My Complaints
                </a>


            </div>


        </div>


    </div>


</div>


</body>

</html>
```
