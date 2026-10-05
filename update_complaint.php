
<?php

session_start();
include "db.php";


/* ================= ADMIN CHECK ================= */

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}


/* ================= GET COMPLAINT ID ================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$complaint_id = intval($_GET['id']);


/* ================= FETCH COMPLAINT ================= */

$sql = "SELECT
            complaints.*,
            users.name,
            users.email,
            users.department
        FROM complaints
        INNER JOIN users
            ON complaints.user_id = users.id
        WHERE complaints.id='$complaint_id'";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) != 1) {
    header("Location: admin.php");
    exit();
}

$complaint = mysqli_fetch_assoc($result);

$message = "";
$message_type = "";


/* ================= UPDATE COMPLAINT ================= */

if (isset($_POST['update'])) {

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $priority = mysqli_real_escape_string(
        $conn,
        $_POST['priority']
    );

    $admin_response = mysqli_real_escape_string(
        $conn,
        $_POST['admin_response']
    );


    $update_sql = "UPDATE complaints
                   SET
                       status='$status',
                       priority='$priority',
                       admin_response='$admin_response'
                   WHERE id='$complaint_id'";


    if (mysqli_query($conn, $update_sql)) {

        header(
            "Location: admin.php?message=updated"
        );

        exit();

    } else {

        $message = "Unable to update complaint. Please try again.";
        $message_type = "error";
    }
}


/* ================= STATUS CLASS ================= */

$current_status = strtolower(
    str_replace(
        " ",
        "-",
        $complaint['status']
    )
);


$current_priority = strtolower(
    $complaint['priority']
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Update Complaint | Office Complaint System
</title>


<style>

/* =========================================================
   RESET
========================================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =========================================================
   BODY
========================================================= */

body {

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        #f5f7fb;

    color:
        #172033;

    min-height:
        100vh;
}


/* =========================================================
   TOPBAR
========================================================= */

.topbar {

    height:
        72px;

    background:
        #ffffff;

    border-bottom:
        1px solid #e6eaf0;

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    padding:
        0 42px;

    position:
        sticky;

    top:
        0;

    z-index:
        100;
}


/* BRAND */

.brand {

    display:
        flex;

    align-items:
        center;

    gap:
        12px;
}


.logo {

    width:
        43px;

    height:
        43px;

    border-radius:
        11px;

    background:
        linear-gradient(
            135deg,
            #172554,
            #2563eb
        );

    color:
        #ffffff;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    font-size:
        14px;

    font-weight:
        800;

    letter-spacing:
        .4px;

    box-shadow:
        0 7px 17px
        rgba(37, 99, 235, .20);
}


.brand-text strong {

    display:
        block;

    font-size:
        15px;

    color:
        #172033;
}


.brand-text span {

    display:
        block;

    font-size:
        10px;

    color:
        #7b8494;

    margin-top:
        3px;
}


/* TOP ACTION */

.top-actions {

    display:
        flex;

    align-items:
        center;

    gap:
        9px;
}


.top-btn {

    text-decoration:
        none;

    padding:
        10px 15px;

    border-radius:
        8px;

    font-size:
        11px;

    font-weight:
        700;

    transition:
        .2s;
}


.dashboard-btn {

    background:
        #eff6ff;

    color:
        #1d4ed8;
}


.dashboard-btn:hover {

    background:
        #dbeafe;
}


.logout-btn {

    background:
        #fef2f2;

    color:
        #dc2626;
}


.logout-btn:hover {

    background:
        #fee2e2;
}


/* =========================================================
   PAGE
========================================================= */

.page {

    max-width:
        1120px;

    margin:
        38px auto;

    padding:
        0 25px;
}


/* PAGE HEADER */

.page-header {

    display:
        flex;

    align-items:
        flex-end;

    justify-content:
        space-between;

    gap:
        20px;

    margin-bottom:
        24px;
}


.page-title h1 {

    font-size:
        29px;

    color:
        #172033;

    margin-bottom:
        7px;
}


.page-title p {

    font-size:
        13px;

    color:
        #6b7280;
}


.complaint-id {

    background:
        #ffffff;

    border:
        1px solid #e3e8ef;

    border-radius:
        10px;

    padding:
        10px 15px;

    font-size:
        11px;

    color:
        #667085;

    white-space:
        nowrap;
}


.complaint-id strong {

    color:
        #1d4ed8;

    font-size:
        13px;
}


/* =========================================================
   ALERT
========================================================= */

.alert {

    padding:
        13px 16px;

    border-radius:
        9px;

    margin-bottom:
        20px;

    font-size:
        12px;

    font-weight:
        600;
}


.alert.error {

    color:
        #b42318;

    background:
        #fef3f2;

    border:
        1px solid #fecdca;
}


/* =========================================================
   MAIN GRID
========================================================= */

.content-grid {

    display:
        grid;

    grid-template-columns:
        1fr 1.05fr;

    gap:
        22px;

    align-items:
        start;
}


/* =========================================================
   CARD
========================================================= */

.card {

    background:
        #ffffff;

    border:
        1px solid #e5eaf1;

    border-radius:
        15px;

    box-shadow:
        0 8px 28px
        rgba(23, 37, 84, .06);

    overflow:
        hidden;
}


.card-header {

    padding:
        20px 23px;

    border-bottom:
        1px solid #edf0f4;
}


.card-header-content {

    display:
        flex;

    align-items:
        center;

    gap:
        11px;
}


.header-icon {

    width:
        35px;

    height:
        35px;

    border-radius:
        9px;

    background:
        #eff6ff;

    color:
        #2563eb;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    font-size:
        14px;

    font-weight:
        800;
}


.card-header h2 {

    font-size:
        15px;

    color:
        #172033;

    margin-bottom:
        3px;
}


.card-header p {

    font-size:
        10px;

    color:
        #8a94a6;
}


.card-body {

    padding:
        23px;
}


/* =========================================================
   COMPLAINT DETAILS
========================================================= */

.info-grid {

    display:
        grid;

    grid-template-columns:
        1fr 1fr;

    gap:
        0 20px;
}


.info-item {

    padding:
        14px 0;

    border-bottom:
        1px solid #f0f2f5;
}


.info-item.full {

    grid-column:
        1 / -1;
}


.info-label {

    font-size:
        9px;

    color:
        #8a94a6;

    text-transform:
        uppercase;

    letter-spacing:
        .7px;

    font-weight:
        700;

    margin-bottom:
        6px;
}


.info-value {

    font-size:
        12px;

    color:
        #263247;

    font-weight:
        600;

    line-height:
        1.5;

    word-break:
        break-word;
}


.description-box {

    background:
        #f8fafc;

    border:
        1px solid #edf1f5;

    border-radius:
        8px;

    padding:
        12px;

    font-weight:
        400;

    color:
        #4b5563;

    line-height:
        1.65;

    margin-top:
        4px;
}


/* =========================================================
   CURRENT STATUS
========================================================= */

.current-state {

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    background:
        #f8fafc;

    border:
        1px solid #edf1f5;

    border-radius:
        10px;

    padding:
        12px 14px;

    margin-top:
        17px;
}


.state-label {

    font-size:
        10px;

    color:
        #7b8494;

    font-weight:
        700;
}


.state-right {

    display:
        flex;

    align-items:
        center;

    gap:
        7px;
}


.status-badge {

    padding:
        5px 9px;

    border-radius:
        20px;

    font-size:
        9px;

    font-weight:
        700;
}


/* STATUS COLORS */

.status-badge.pending {

    background:
        #fff7ed;

    color:
        #c2410c;
}


.status-badge.in-progress {

    background:
        #eff6ff;

    color:
        #2563eb;
}


.status-badge.resolved {

    background:
        #ecfdf5;

    color:
        #047857;
}


.status-badge.rejected {

    background:
        #fef2f2;

    color:
        #dc2626;
}


/* =========================================================
   FORM
========================================================= */

.form-group {

    margin-bottom:
        20px;
}


label {

    display:
        block;

    font-size:
        11px;

    font-weight:
        700;

    color:
        #344054;

    margin-bottom:
        8px;
}


.required {

    color:
        #ef4444;
}


select,
textarea {

    width:
        100%;

    border:
        1px solid #d8dee7;

    border-radius:
        9px;

    padding:
        12px 13px;

    background:
        #ffffff;

    color:
        #172033;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    font-size:
        12px;

    outline:
        none;

    transition:
        .2s;
}


select:focus,
textarea:focus {

    border-color:
        #2563eb;

    box-shadow:
        0 0 0 3px
        rgba(37, 99, 235, .09);
}


textarea {

    min-height:
        150px;

    resize:
        vertical;

    line-height:
        1.6;
}


.form-hint {

    font-size:
        9px;

    color:
        #98a2b3;

    margin-top:
        6px;

    line-height:
        1.5;
}


/* FORM TWO COLUMNS */

.form-row {

    display:
        grid;

    grid-template-columns:
        1fr 1fr;

    gap:
        16px;
}


/* =========================================================
   RESPONSE BOX
========================================================= */

.response-section {

    background:
        #f8fbff;

    border:
        1px solid #e3efff;

    border-radius:
        10px;

    padding:
        15px;

}


.response-title {

    display:
        flex;

    align-items:
        center;

    gap:
        8px;

    margin-bottom:
        12px;
}


.response-dot {

    width:
        7px;

    height:
        7px;

    border-radius:
        50%;

    background:
        #2563eb;
}


.response-title span {

    font-size:
        11px;

    font-weight:
        700;

    color:
        #1e3a8a;
}


/* =========================================================
   ACTIONS
========================================================= */

.actions {

    display:
        flex;

    gap:
        10px;

    margin-top:
        22px;

    padding-top:
        19px;

    border-top:
        1px solid #edf0f4;
}


.update-btn {

    flex:
        1;

    border:
        none;

    padding:
        13px 18px;

    border-radius:
        9px;

    background:
        linear-gradient(
            135deg,
            #1e3a8a,
            #2563eb
        );

    color:
        #ffffff;

    font-size:
        12px;

    font-weight:
        700;

    cursor:
        pointer;

    box-shadow:
        0 7px 17px
        rgba(37, 99, 235, .18);

    transition:
        .2s;
}


.update-btn:hover {

    transform:
        translateY(-1px);

    box-shadow:
        0 9px 21px
        rgba(37, 99, 235, .25);
}


.cancel-btn {

    text-decoration:
        none;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    padding:
        13px 20px;

    border-radius:
        9px;

    background:
        #f1f5f9;

    color:
        #475569;

    font-size:
        11px;

    font-weight:
        700;

    transition:
        .2s;
}


.cancel-btn:hover {

    background:
        #e2e8f0;
}


/* =========================================================
   FOOTER
========================================================= */

.footer {

    text-align:
        center;

    padding:
        30px 20px;

    font-size:
        10px;

    color:
        #98a2b3;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 850px) {

    .content-grid {

        grid-template-columns:
            1fr;
    }

}


@media (max-width: 650px) {

    .topbar {

        padding:
            0 18px;
    }


    .brand-text {

        display:
            none;
    }


    .page {

        margin:
            25px auto;

        padding:
            0 15px;
    }


    .page-header {

        align-items:
            flex-start;

        flex-direction:
            column;
    }


    .page-title h1 {

        font-size:
            25px;
    }


    .info-grid {

        grid-template-columns:
            1fr;
    }


    .info-item.full {

        grid-column:
            auto;
    }


    .form-row {

        grid-template-columns:
            1fr;

        gap:
            0;
    }


    .card-body {

        padding:
            19px;
    }


    .actions {

        flex-direction:
            column-reverse;
    }


    .cancel-btn {

        width:
            100%;
    }


    .top-btn {

        padding:
            9px 11px;

        font-size:
            10px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     TOPBAR
===================================================== -->

<div class="topbar">


    <div class="brand">

        <div class="logo">
            OCS
        </div>


        <div class="brand-text">

            <strong>
                Office Complaint System
            </strong>

            <span>
                Administration Panel
            </span>

        </div>

    </div>


    <div class="top-actions">

        <a
            href="admin.php"
            class="top-btn dashboard-btn"
        >
            Dashboard
        </a>

        <a
            href="logout.php"
            class="top-btn logout-btn"
        >
            Logout
        </a>

    </div>

</div>



<!-- =====================================================
     PAGE
===================================================== -->

<div class="page">


    <!-- PAGE HEADER -->

    <div class="page-header">


        <div class="page-title">

            <h1>
                Update Complaint
            </h1>

            <p>
                Review the submitted complaint and update its current status.
            </p>

        </div>


        <div class="complaint-id">

            Complaint ID

            <strong>
                #<?php echo $complaint['id']; ?>
            </strong>

        </div>


    </div>



    <!-- ERROR MESSAGE -->

    <?php if ($message != "") { ?>

        <div class="alert error">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php } ?>



    <!-- CONTENT -->

    <div class="content-grid">


        <!-- =================================================
             COMPLAINT DETAILS
        ================================================== -->

        <div class="card">


            <div class="card-header">

                <div class="card-header-content">

                    <div class="header-icon">
                        #
                    </div>

                    <div>

                        <h2>
                            Complaint Details
                        </h2>

                        <p>
                            Information submitted by the employee
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">


                <div class="info-grid">


                    <!-- EMPLOYEE -->

                    <div class="info-item">

                        <div class="info-label">
                            Employee
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $complaint['name']
                            );
                            ?>

                        </div>

                    </div>


                    <!-- DEPARTMENT -->

                    <div class="info-item">

                        <div class="info-label">
                            Department
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $complaint['department']
                            );
                            ?>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="info-item full">

                        <div class="info-label">
                            Email Address
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $complaint['email']
                            );
                            ?>

                        </div>

                    </div>


                    <!-- TITLE -->

                    <div class="info-item full">

                        <div class="info-label">
                            Complaint Title
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $complaint['title']
                            );
                            ?>

                        </div>

                    </div>


                    <!-- CATEGORY -->

                    <div class="info-item">

                        <div class="info-label">
                            Category
                        </div>

                        <div class="info-value">

                            <?php
                            echo htmlspecialchars(
                                $complaint['category']
                            );
                            ?>

                        </div>

                    </div>


                    <!-- CREATED -->

                    <div class="info-item">

                        <div class="info-label">
                            Submitted
                        </div>

                        <div class="info-value">

                            <?php
                            echo date(
                                "d M Y",
                                strtotime(
                                    $complaint['created_at']
                                )
                            );
                            ?>

                        </div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="info-item full">

                        <div class="info-label">
                            Description
                        </div>

                        <div class="description-box">

                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $complaint['description']
                                )
                            );
                            ?>

                        </div>

                    </div>


                </div>



                <!-- CURRENT STATE -->

                <div class="current-state">

                    <div class="state-label">
                        Current Status
                    </div>


                    <div class="state-right">

                        <span
                            class="status-badge <?php echo $current_status; ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $complaint['status']
                            );
                            ?>

                        </span>


                        <span
                            class="status-badge <?php echo $current_priority; ?>"
                            style="
                                background:#f8fafc;
                                color:#475569;
                            "
                        >

                            <?php
                            echo htmlspecialchars(
                                $complaint['priority']
                            );
                            ?>

                        </span>

                    </div>

                </div>


            </div>

        </div>



        <!-- =================================================
             UPDATE FORM
        ================================================== -->

        <div class="card">


            <div class="card-header">

                <div class="card-header-content">

                    <div
                        class="header-icon"
                        style="
                            background:#ecfdf5;
                            color:#047857;
                        "
                    >
                        ✓
                    </div>

                    <div>

                        <h2>
                            Manage Complaint
                        </h2>

                        <p>
                            Update status, priority and response
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body">


                <form
                    method="POST"
                >


                    <!-- STATUS + PRIORITY -->

                    <div class="form-row">


                        <!-- STATUS -->

                        <div class="form-group">

                            <label>
                                Status
                                <span class="required">*</span>
                            </label>

                            <select
                                name="status"
                                required
                            >

                                <option
                                    value="Pending"
                                    <?php
                                    if (
                                        $complaint['status']
                                        == 'Pending'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Pending
                                </option>


                                <option
                                    value="In Progress"
                                    <?php
                                    if (
                                        $complaint['status']
                                        == 'In Progress'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    In Progress
                                </option>


                                <option
                                    value="Resolved"
                                    <?php
                                    if (
                                        $complaint['status']
                                        == 'Resolved'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Resolved
                                </option>


                                <option
                                    value="Rejected"
                                    <?php
                                    if (
                                        $complaint['status']
                                        == 'Rejected'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Rejected
                                </option>

                            </select>

                            <div class="form-hint">
                                Select the current progress of the complaint.
                            </div>

                        </div>



                        <!-- PRIORITY -->

                        <div class="form-group">

                            <label>
                                Priority
                                <span class="required">*</span>
                            </label>

                            <select
                                name="priority"
                                required
                            >

                                <option
                                    value="Low"
                                    <?php
                                    if (
                                        $complaint['priority']
                                        == 'Low'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Low
                                </option>


                                <option
                                    value="Medium"
                                    <?php
                                    if (
                                        $complaint['priority']
                                        == 'Medium'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Medium
                                </option>


                                <option
                                    value="High"
                                    <?php
                                    if (
                                        $complaint['priority']
                                        == 'High'
                                    ) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    High
                                </option>

                            </select>

                            <div class="form-hint">
                                Set the appropriate priority level.
                            </div>

                        </div>

                    </div>



                    <!-- RESPONSE -->

                    <div class="response-section">


                        <div class="response-title">

                            <span class="response-dot"></span>

                            <span>
                                Admin Response
                            </span>

                        </div>


                        <textarea
                            name="admin_response"
                            placeholder="Write a professional response for the employee..."
                        ><?php

                        echo htmlspecialchars(
                            $complaint['admin_response']
                            ?? ''
                        );

                        ?></textarea>


                        <div class="form-hint">
                            This response will be visible to the employee from the My Complaints page.
                        </div>


                    </div>



                    <!-- ACTIONS -->

                    <div class="actions">


                        <a
                            href="admin.php"
                            class="cancel-btn"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            name="update"
                            class="update-btn"
                        >
                            Update Complaint
                        </button>


                    </div>


                </form>


            </div>

        </div>


    </div>

</div>



<!-- FOOTER -->

<div class="footer">

    © 2026 Office Complaint System · Administration Panel

</div>


</body>

</html>
