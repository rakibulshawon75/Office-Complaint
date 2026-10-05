
<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$message_type = "";

if (isset($_POST['submit'])) {

    $user_id = $_SESSION['user_id'];

    $title = mysqli_real_escape_string(
        $conn,
        $_POST['title']
    );

    $category = mysqli_real_escape_string(
        $conn,
        $_POST['category']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $priority = mysqli_real_escape_string(
        $conn,
        $_POST['priority']
    );

    $sql = "INSERT INTO complaints
            (user_id, title, category, description, priority)
            VALUES
            ('$user_id', '$title', '$category', '$description', '$priority')";

    if (mysqli_query($conn, $sql)) {

        $message = "Complaint submitted successfully!";
        $message_type = "success";

    } else {

        $message = "Unable to submit complaint. Please try again.";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Submit New Complaint | Office Complaint System</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f7fb;
    color: #172033;
}

/* ================= TOPBAR ================= */

.topbar {
    height: 72px;
    background: #ffffff;
    border-bottom: 1px solid #e5eaf2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 42px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: linear-gradient(
        135deg,
        #1e3a8a,
        #2563eb
    );
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 15px;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.22);
}

.brand-text strong {
    display: block;
    font-size: 15px;
    color: #172033;
}

.brand-text span {
    display: block;
    font-size: 10px;
    color: #718096;
    margin-top: 2px;
}

.top-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.top-btn {
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    transition: 0.2s;
}

.dashboard-btn {
    color: #1e3a8a;
    background: #eff6ff;
}

.dashboard-btn:hover {
    background: #dbeafe;
}

.complaints-btn {
    color: white;
    background: #2563eb;
}

.complaints-btn:hover {
    background: #1d4ed8;
}

/* ================= PAGE ================= */

.page {
    max-width: 1050px;
    margin: 42px auto;
    padding: 0 25px;
}

.page-header {
    margin-bottom: 25px;
}

.page-header h1 {
    font-size: 30px;
    color: #172033;
    margin-bottom: 7px;
}

.page-header p {
    font-size: 14px;
    color: #6b7280;
}

/* ================= MAIN CARD ================= */

.form-card {
    background: #ffffff;
    border: 1px solid #e5eaf2;
    border-radius: 16px;
    box-shadow: 0 10px 35px rgba(30, 58, 138, 0.08);
    overflow: hidden;
}

.form-header {
    padding: 23px 28px;
    background: linear-gradient(
        135deg,
        #1e3a8a,
        #2563eb
    );
    color: white;
}

.form-header h2 {
    font-size: 18px;
    margin-bottom: 5px;
}

.form-header p {
    font-size: 11px;
    opacity: 0.88;
}

.form-body {
    padding: 30px;
}

/* ================= ALERT ================= */

.alert {
    padding: 13px 16px;
    border-radius: 9px;
    margin-bottom: 22px;
    font-size: 13px;
    font-weight: 600;
}

.alert.success {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.alert.error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

/* ================= FORM ================= */

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full {
    grid-column: 1 / -1;
}

label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #344054;
    margin-bottom: 8px;
}

.required {
    color: #ef4444;
}

input,
select,
textarea {
    width: 100%;
    border: 1px solid #d9e0ea;
    border-radius: 9px;
    padding: 13px 14px;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    color: #172033;
    background: #ffffff;
    outline: none;
    transition: 0.2s;
}

input::placeholder,
textarea::placeholder {
    color: #9aa4b2;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
}

textarea {
    min-height: 145px;
    resize: vertical;
    line-height: 1.6;
}

/* ================= PRIORITY ================= */

.priority-note {
    margin-top: 6px;
    font-size: 10px;
    color: #8a94a6;
}

/* ================= BOTTOM ================= */

.form-footer {
    margin-top: 8px;
    padding-top: 22px;
    border-top: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.info-text {
    font-size: 11px;
    color: #7b8494;
    line-height: 1.5;
}

.submit-btn {
    border: none;
    padding: 13px 25px;
    border-radius: 9px;
    background: linear-gradient(
        135deg,
        #1e3a8a,
        #2563eb
    );
    color: white;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
    box-shadow: 0 7px 18px rgba(37, 99, 235, 0.20);
}

.submit-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 9px 22px rgba(37, 99, 235, 0.28);
}

/* ================= FOOTER ================= */

.footer {
    text-align: center;
    padding: 30px 20px;
    font-size: 10px;
    color: #98a2b3;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 700px) {

    .topbar {
        padding: 0 18px;
    }

    .brand-text {
        display: none;
    }

    .top-btn {
        padding: 9px 11px;
        font-size: 10px;
    }

    .page {
        margin: 28px auto;
        padding: 0 15px;
    }

    .page-header h1 {
        font-size: 25px;
    }

    .form-body {
        padding: 22px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .form-group.full {
        grid-column: auto;
    }

    .form-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .submit-btn {
        width: 100%;
    }
}

</style>

</head>

<body>

<!-- TOPBAR -->

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
                Employee Portal
            </span>

        </div>

    </div>


    <div class="top-actions">

        <a
            href="dashboard.php"
            class="top-btn dashboard-btn"
        >
            Dashboard
        </a>

        <a
            href="my_complaints.php"
            class="top-btn complaints-btn"
        >
            My Complaints
        </a>

    </div>

</div>


<!-- PAGE -->

<div class="page">

    <div class="page-header">

        <h1>
            Submit New Complaint
        </h1>

        <p>
            Report an issue or concern to the appropriate department.
        </p>

    </div>


    <div class="form-card">

        <div class="form-header">

            <h2>
                Complaint Details
            </h2>

            <p>
                Please provide accurate information so your complaint can be reviewed efficiently.
            </p>

        </div>


        <div class="form-body">

            <?php if ($message != "") { ?>

                <div class="alert <?php echo $message_type; ?>">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php } ?>


            <form method="POST">

                <div class="form-grid">


                    <!-- TITLE -->

                    <div class="form-group full">

                        <label>
                            Complaint Title
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            placeholder="Enter a short and clear complaint title"
                            required
                        >

                    </div>


                    <!-- CATEGORY -->

                    <div class="form-group">

                        <label>
                            Category
                            <span class="required">*</span>
                        </label>

                        <select
                            name="category"
                            required
                        >

                            <option value="">
                                Select Category
                            </option>

                            <option value="IT">
                                IT
                            </option>

                            <option value="HR">
                                HR
                            </option>

                            <option value="Management">
                                Management
                            </option>

                            <option value="Workplace">
                                Workplace
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

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

                            <option value="Low">
                                Low
                            </option>

                            <option
                                value="Medium"
                                selected
                            >
                                Medium
                            </option>

                            <option value="High">
                                High
                            </option>

                        </select>

                        <div class="priority-note">
                            Select High only for urgent issues.
                        </div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="form-group full">

                        <label>
                            Complaint Description
                            <span class="required">*</span>
                        </label>

                        <textarea
                            name="description"
                            placeholder="Describe the issue clearly, including relevant details..."
                            required
                        ></textarea>

                    </div>


                </div>


                <div class="form-footer">

                    <div class="info-text">

                        Your complaint will be reviewed by the administrator.<br>
                        You can track its status from <strong>My Complaints</strong>.

                    </div>


                    <button
                        type="submit"
                        name="submit"
                        class="submit-btn"
                    >
                        Submit Complaint
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<div class="footer">

    © 2026 Office Complaint System · Employee Portal

</div>

</body>

</html>

