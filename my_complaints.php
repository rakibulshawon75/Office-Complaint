<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM complaints
        WHERE user_id='$user_id'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>

    <title>My Complaints - Office Complaint System</title>

    <link rel="stylesheet" href="style.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        body {
            min-height: 100vh;
            background: #F0FDFA;
            color: #1E293B;
        }


        /* ================= TOPBAR ================= */

        .topbar {
            background: linear-gradient(
                135deg,
                #0F766E,
                #0D9488
            );

            color: white;

            padding: 18px 6%;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow:
                0 4px 15px rgba(15,118,110,0.20);
        }


        .topbar h2 {
            color: white;
            margin: 0;
            font-size: 21px;
        }


        .back-btn {
            color: white;
            text-decoration: none;

            background: rgba(255,255,255,0.14);

            padding: 9px 17px;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;

            transition: 0.2s;
        }


        .back-btn:hover {
            color: white;
            background: rgba(255,255,255,0.25);
        }


        /* ================= PAGE ================= */

        .page {
            width: 94%;
            max-width: 1250px;

            margin: 40px auto;
        }


        /* ================= TITLE ================= */

        .page-title {
            background: white;

            padding: 28px;

            border-radius: 18px;

            box-shadow:
                0 6px 25px rgba(13,148,136,0.09);

            margin-bottom: 25px;

            border-left: 5px solid #0D9488;
        }


        .page-title h1 {
            margin: 0 0 8px;

            color: #0F766E;

            font-size: 26px;
        }


        .page-title p {
            margin: 0;

            color: #64748B;

            font-size: 14px;
        }


        /* ================= TABLE CARD ================= */

        .table-card {
            background: white;

            padding: 20px;

            border-radius: 18px;

            box-shadow:
                0 6px 25px rgba(13,148,136,0.09);

            overflow-x: auto;
        }


        /* ================= TABLE ================= */

        table {
            width: 100%;

            border-collapse: collapse;

            box-shadow: none;

            min-width: 900px;
        }


        th {
            text-align: left;

            white-space: nowrap;

            background: #F0FDFA;

            color: #0F766E;

            padding: 15px 13px;

            font-size: 12px;

            font-weight: 700;

            border-bottom: 2px solid #CCFBF1;
        }


        td {
            vertical-align: top;

            padding: 15px 13px;

            font-size: 13px;

            color: #334155;

            border-bottom: 1px solid #E2E8F0;

            line-height: 1.5;
        }


        tr:last-child td {
            border-bottom: none;
        }


        tbody tr:hover {
            background: #F8FFFE;
        }


        /* ================= TITLE ================= */

        .complaint-title {
            color: #0F766E;

            font-weight: 700;

            max-width: 220px;
        }


        .complaint-id {
            color: #64748B;

            font-weight: 700;

            white-space: nowrap;
        }


        /* ================= STATUS ================= */

        .status {
            display: inline-block;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;
        }


        .pending {
            background: #FEF3C7;
            color: #92400E;
        }


        .in-progress {
            background: #DBEAFE;
            color: #1D4ED8;
        }


        .resolved {
            background: #DCFCE7;
            color: #15803D;
        }


        .rejected {
            background: #FEE2E2;
            color: #B91C1C;
        }


        /* ================= PRIORITY ================= */

        .priority {
            display: inline-block;

            padding: 5px 10px;

            border-radius: 7px;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;
        }


        .priority-high {
            background: #FEF2F2;
            color: #DC2626;
        }


        .priority-medium {
            background: #FFF7ED;
            color: #D97706;
        }


        .priority-low {
            background: #F0FDF4;
            color: #15803D;
        }


        /* ================= ADMIN RESPONSE ================= */

        .response {
            max-width: 260px;

            color: #475569;

            line-height: 1.5;
        }


        .no-response {
            color: #94A3B8;

            font-style: italic;

            font-size: 12px;
        }


        /* ================= DATE ================= */

        .date {
            white-space: nowrap;

            color: #64748B;

            font-size: 12px;
        }


        /* ================= NO DATA ================= */

        .no-data {
            text-align: center;

            padding: 50px 20px;

            color: #64748B;

            font-size: 14px;
        }


        .no-data-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 12px;

            border-radius: 50%;

            background: #F0FDFA;

            color: #0D9488;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 22px;

            font-weight: bold;
        }


        /* ================= MOBILE ================= */

        @media (max-width: 600px) {

            .topbar {
                padding: 16px 5%;
            }


            .topbar h2 {
                font-size: 17px;
            }


            .page {
                width: 92%;

                margin: 25px auto;
            }


            .page-title {
                padding: 22px;
            }


            .page-title h1 {
                font-size: 22px;
            }


            .table-card {
                padding: 12px;
            }

        }

    </style>

</head>


<body>


<!-- ================= TOPBAR ================= -->

<div class="topbar">

    <h2>
        Office Complaint System
    </h2>


    <a
        class="back-btn"
        href="dashboard.php"
    >
        ← Dashboard
    </a>

</div>


<!-- ================= MAIN PAGE ================= -->

<div class="page">


    <!-- PAGE TITLE -->

    <div class="page-title">

        <h1>
            My Complaints
        </h1>

        <p>
            Track your submitted complaints and view admin responses.
        </p>

    </div>


    <!-- TABLE -->

    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Priority</th>

                    <th>Status</th>

                    <th>Admin Response</th>

                    <th>Date</th>

                </tr>

            </thead>


            <tbody>


            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

                    $status_class =
                        strtolower($row['status']);

                    $status_class =
                        str_replace(
                            " ",
                            "-",
                            $status_class
                        );

                    $priority_class =
                        strtolower($row['priority']);

            ?>


                <tr>


                    <!-- ID -->

                    <td>

                        <span class="complaint-id">

                            #<?php
                            echo $row['id'];
                            ?>

                        </span>

                    </td>


                    <!-- TITLE -->

                    <td>

                        <div class="complaint-title">

                            <?php
                            echo htmlspecialchars(
                                $row['title']
                            );
                            ?>

                        </div>

                    </td>


                    <!-- CATEGORY -->

                    <td>

                        <?php
                        echo htmlspecialchars(
                            $row['category']
                        );
                        ?>

                    </td>


                    <!-- PRIORITY -->

                    <td>

                        <span
                            class="priority priority-<?php
                            echo $priority_class;
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $row['priority']
                            );
                            ?>

                        </span>

                    </td>


                    <!-- STATUS -->

                    <td>

                        <span
                            class="status <?php
                            echo $status_class;
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $row['status']
                            );
                            ?>

                        </span>

                    </td>


                    <!-- ADMIN RESPONSE -->

                    <td>

                        <div class="response">

                            <?php

                            if (!empty(
                                $row['admin_response']
                            )) {

                                echo htmlspecialchars(
                                    $row['admin_response']
                                );

                            } else {

                            ?>

                                <span class="no-response">
                                    No response yet
                                </span>

                            <?php

                            }

                            ?>

                        </div>

                    </td>


                    <!-- DATE -->

                    <td>

                        <span class="date">

                            <?php

                            echo date(
                                "d M Y",
                                strtotime(
                                    $row['created_at']
                                )
                            );

                            ?>

                        </span>

                    </td>


                </tr>


            <?php

                }

            } else {

            ?>


                <tr>

                    <td
                        colspan="7"
                        class="no-data"
                    >

                        <div class="no-data-icon">
                            !
                        </div>

                        No complaints submitted yet.

                    </td>

                </tr>


            <?php

            }

            ?>


            </tbody>

        </table>

    </div>


</div>


</body>

</html>