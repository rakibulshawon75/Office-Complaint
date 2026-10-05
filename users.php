
<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}


/* =========================
   SEARCH & FILTER
========================= */

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$department = isset($_GET['department']) ? $_GET['department'] : "";


/* =========================
   EMPLOYEE QUERY
========================= */

$sql = "SELECT * FROM users WHERE 1=1";

if ($search != "") {

    $safe_search = mysqli_real_escape_string($conn, $search);

    $sql .= " AND (
                name LIKE '%$safe_search%'
                OR email LIKE '%$safe_search%'
              )";
}

if ($department != "") {

    $safe_department = mysqli_real_escape_string($conn, $department);

    $sql .= " AND department='$safe_department'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);


/* =========================
   DEPARTMENT LIST
========================= */

$department_sql = "SELECT DISTINCT department
                   FROM users
                   WHERE department IS NOT NULL
                   AND department != ''
                   ORDER BY department ASC";

$department_result = mysqli_query($conn, $department_sql);


/* =========================
   TOTAL EMPLOYEES
========================= */

$count_sql = "SELECT COUNT(*) AS total FROM users";

$total_users = mysqli_fetch_assoc(
    mysqli_query($conn, $count_sql)
)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Employee Management | Admin</title>

    <link rel="stylesheet" href="style.css">


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            min-height: 100vh;

            margin: 0;

            background: #F0FDFA;

            font-family: Arial, sans-serif;

            color: #0F172A;
        }


        /* =========================
           TOP BAR
        ========================= */

        .topbar {

            background:
                linear-gradient(
                    135deg,
                    #0F766E,
                    #0D9488,
                    #0891B2
                );

            color: white;

            padding: 17px 5%;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow:
                0 5px 18px rgba(15, 118, 110, 0.20);
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .brand-icon {

            width: 38px;
            height: 38px;

            border-radius: 10px;

            background: rgba(255,255,255,0.16);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;

            font-weight: bold;
        }


        .brand-text h2 {

            margin: 0;

            color: white;

            font-size: 19px;

            font-weight: 700;
        }


        .brand-text span {

            display: block;

            margin-top: 3px;

            color: rgba(255,255,255,0.78);

            font-size: 11px;
        }


        .top-actions {

            display: flex;

            gap: 9px;

            align-items: center;
        }


        .top-btn {

            color: white;

            padding: 9px 15px;

            border-radius: 8px;

            text-decoration: none;

            background: rgba(255,255,255,0.14);

            border: 1px solid rgba(255,255,255,0.15);

            font-size: 13px;

            font-weight: 600;

            transition: 0.2s;
        }


        .top-btn:hover {

            color: white;

            text-decoration: none;

            background: rgba(255,255,255,0.25);

            transform: translateY(-1px);
        }


        .logout-btn {

            background: rgba(220,38,38,0.18);

            border-color: rgba(255,255,255,0.12);
        }


        .logout-btn:hover {

            background: rgba(220,38,38,0.32);
        }


        /* =========================
           MAIN
        ========================= */

        .dashboard {

            width: 94%;

            max-width: 1300px;

            margin: 38px auto 50px;
        }


        .page-heading {

            margin-bottom: 25px;
        }


        .page-heading h1 {

            margin: 0 0 7px;

            font-size: 28px;

            color: #0F172A;

            border-left: 5px solid #0D9488;

            padding-left: 13px;
        }


        .page-heading p {

            margin: 0;

            padding-left: 18px;

            color: #64748B;

            font-size: 14px;
        }


        /* =========================
           EMPLOYEE COUNT
        ========================= */

        .employee-count {

            background: white;

            padding: 22px 25px;

            border-radius: 15px;

            border: 1px solid #CCFBF1;

            box-shadow:
                0 6px 22px rgba(15,118,110,0.08);

            margin-bottom: 22px;

            display: flex;

            align-items: center;

            gap: 18px;
        }


        .count-icon {

            width: 55px;
            height: 55px;

            border-radius: 13px;

            background: #CCFBF1;

            color: #0F766E;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 21px;

            font-weight: bold;
        }


        .count-text p {

            margin: 0 0 4px;

            color: #64748B;

            font-size: 13px;

            font-weight: 600;
        }


        .count-text h2 {

            margin: 0;

            color: #0F766E;

            font-size: 30px;

            line-height: 1;
        }


        /* =========================
           TABLE SECTION
        ========================= */

        .user-section {

            background: white;

            padding: 23px;

            border-radius: 18px;

            border: 1px solid #CCFBF1;

            box-shadow:
                0 7px 25px rgba(15,118,110,0.07);

            overflow-x: auto;
        }


        .section-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 18px;
        }


        .section-header h2 {

            margin: 0;

            color: #0F172A;

            font-size: 18px;
        }


        .employee-label {

            background: #ECFEFF;

            color: #0E7490;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;
        }


        /* =========================
           FILTER
        ========================= */

        .filter-box {

            background: #F0FDFA;

            border: 1px solid #CCFBF1;

            padding: 16px;

            border-radius: 12px;

            margin-bottom: 22px;
        }


        .filter-form {

            display: flex;

            gap: 10px;

            align-items: center;

            flex-wrap: wrap;

            max-width: none;

            padding: 0;

            margin: 0;

            background: transparent;

            box-shadow: none;
        }


        .filter-form input {

            flex: 1;

            min-width: 240px;

            margin: 0;

            padding: 12px 13px;

            border: 1px solid #CBD5E1;

            border-radius: 8px;

            background: white;

            outline: none;

            font-size: 14px;
        }


        .filter-form input:focus {

            border-color: #0D9488;

            box-shadow:
                0 0 0 3px rgba(13,148,136,0.09);
        }


        .filter-form select {

            width: 190px;

            margin: 0;

            padding: 12px 13px;

            border: 1px solid #CBD5E1;

            border-radius: 8px;

            background: white;

            outline: none;

            font-size: 14px;

            color: #334155;
        }


        .filter-form select:focus {

            border-color: #0D9488;
        }


        .filter-btn {

            margin: 0;

            border: none;

            padding: 12px 18px;

            border-radius: 8px;

            background:
                linear-gradient(
                    135deg,
                    #0F766E,
                    #0D9488
                );

            color: white;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;
        }


        .filter-btn:hover {

            transform: translateY(-1px);

            box-shadow:
                0 5px 13px rgba(13,148,136,0.20);
        }


        .clear-btn {

            display: inline-block;

            background: white;

            color: #0F766E;

            padding: 11px 17px;

            border-radius: 8px;

            border: 1px solid #99F6E4;

            font-size: 14px;

            font-weight: 600;

            text-decoration: none;
        }


        .clear-btn:hover {

            background: #CCFBF1;

            color: #115E59;

            text-decoration: none;
        }


        /* =========================
           TABLE
        ========================= */

        table {

            width: 100%;

            border-collapse: collapse;

            box-shadow: none;

            min-width: 800px;

            margin: 0;
        }


        th {

            background: #F0FDFA;

            color: #0F766E;

            padding: 14px 13px;

            text-align: left;

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 0.4px;

            border-bottom: 1px solid #CCFBF1;

            white-space: nowrap;
        }


        td {

            padding: 15px 13px;

            border-bottom: 1px solid #E2E8F0;

            vertical-align: middle;

            font-size: 14px;

            color: #334155;
        }


        tr:last-child td {

            border-bottom: none;
        }


        tbody tr:hover {

            background: #F8FFFE;
        }


        .user-id {

            color: #0D9488;

            font-weight: 700;

            font-size: 13px;
        }


        .employee-name {

            color: #0F172A;

            font-weight: 600;
        }


        .email {

            color: #475569;

            font-size: 13px;
        }


        .department-badge {

            display: inline-block;

            background: #CCFBF1;

            color: #0F766E;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;
        }


        .date-badge {

            color: #64748B;

            font-size: 13px;
        }


        .empty-state {

            text-align: center;

            padding: 45px 20px !important;

            color: #64748B;
        }


        .empty-icon {

            width: 48px;
            height: 48px;

            margin: 0 auto 12px;

            border-radius: 50%;

            background: #F0FDFA;

            color: #0D9488;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;
        }


        .empty-state strong {

            display: block;

            color: #334155;

            margin-bottom: 5px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            .topbar {

                flex-direction: column;

                gap: 14px;

                align-items: flex-start;
            }


            .top-actions {

                width: 100%;
            }


            .top-btn {

                flex: 1;

                text-align: center;
            }


            .dashboard {

                width: 92%;

                margin-top: 28px;
            }


            .page-heading h1 {

                font-size: 24px;
            }


            .section-header {

                align-items: flex-start;

                gap: 10px;

                flex-direction: column;
            }


            .filter-form {

                flex-direction: column;

                align-items: stretch;
            }


            .filter-form input,
            .filter-form select,
            .filter-btn,
            .clear-btn {

                width: 100%;

                min-width: 0;

                text-align: center;
            }


            .employee-count {

                padding: 18px;
            }
        }

    </style>

</head>


<body>


<!-- =========================
     TOP BAR
========================= -->

<div class="topbar">

    <div class="brand">

        <div class="brand-icon">
            O
        </div>

        <div class="brand-text">

            <h2>Office Complaint System</h2>

            <span>Administration Panel</span>

        </div>

    </div>


    <div class="top-actions">

        <a href="admin.php" class="top-btn">
            Dashboard
        </a>

        <a href="logout.php" class="top-btn logout-btn">
            Logout
        </a>

    </div>

</div>


<!-- =========================
     MAIN
========================= -->

<div class="dashboard">


    <!-- PAGE HEADING -->

    <div class="page-heading">

        <h1>Employee Management</h1>

        <p>
            View and manage all registered employees.
        </p>

    </div>


    <!-- EMPLOYEE COUNT -->

    <div class="employee-count">

        <div class="count-icon">
            U
        </div>

        <div class="count-text">

            <p>
                Total Registered Employees
            </p>

            <h2>
                <?php echo $total_users; ?>
            </h2>

        </div>

    </div>


    <!-- USER SECTION -->

    <div class="user-section">


        <div class="section-header">

            <h2>Registered Employees</h2>

            <span class="employee-label">
                Employee Directory
            </span>

        </div>


        <!-- FILTER -->

        <div class="filter-box">

            <form method="GET" class="filter-form">


                <input
                    type="text"
                    name="search"
                    placeholder="Search by employee name or email..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >


                <select name="department">

                    <option value="">
                        All Departments
                    </option>


                    <?php

                    while ($dept = mysqli_fetch_assoc($department_result)) {

                    ?>

                        <option
                            value="<?php echo htmlspecialchars($dept['department']); ?>"
                            <?php
                            if ($department == $dept['department']) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $dept['department']
                            );
                            ?>

                        </option>

                    <?php

                    }

                    ?>

                </select>


                <button
                    type="submit"
                    class="filter-btn"
                >
                    Search
                </button>


                <a
                    href="users.php"
                    class="clear-btn"
                >
                    Clear
                </a>


            </form>

        </div>


        <!-- TABLE -->

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Department</th>

                    <th>Registered Date</th>

                </tr>

            </thead>


            <tbody>

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

            ?>

                <tr>

                    <td class="user-id">

                        #<?php echo $row['id']; ?>

                    </td>


                    <td class="employee-name">

                        <?php
                        echo htmlspecialchars(
                            $row['name']
                        );
                        ?>

                    </td>


                    <td class="email">

                        <?php
                        echo htmlspecialchars(
                            $row['email']
                        );
                        ?>

                    </td>


                    <td>

                        <span class="department-badge">

                            <?php
                            echo htmlspecialchars(
                                $row['department']
                            );
                            ?>

                        </span>

                    </td>


                    <td class="date-badge">

                        <?php
                        echo date(
                            "d M Y",
                            strtotime($row['created_at'])
                        );
                        ?>

                    </td>

                </tr>


            <?php

                }

            } else {

            ?>

                <tr>

                    <td
                        colspan="5"
                        class="empty-state"
                    >

                        <div class="empty-icon">
                            -
                        </div>

                        <strong>
                            No employees found
                        </strong>

                        Try changing your search or department filter.

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

