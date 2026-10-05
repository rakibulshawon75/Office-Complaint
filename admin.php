
<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* ================= DELETE SELECTED ================= */

if (isset($_POST['delete_selected'])) {

    if (!empty($_POST['selected_complaints'])) {

        $selected_ids = $_POST['selected_complaints'];
        $safe_ids = array();

        foreach ($selected_ids as $id) {
            $id = intval($id);

            if ($id > 0) {
                $safe_ids[] = $id;
            }
        }

        if (!empty($safe_ids)) {

            $id_list = implode(",", $safe_ids);

            $delete_sql = "DELETE FROM complaints
                           WHERE id IN ($id_list)";

            if (mysqli_query($conn, $delete_sql)) {
                header("Location: admin.php?message=deleted");
                exit();
            } else {
                header("Location: admin.php?message=error");
                exit();
            }
        }

    } else {

        header("Location: admin.php?message=no_selection");
        exit();
    }
}


/* ================= SEARCH & FILTER ================= */

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$status_filter = "";

if (isset($_GET['status'])) {
    $status_filter = mysqli_real_escape_string($conn, $_GET['status']);
}


/* ================= COMPLAINT QUERY ================= */

$sql = "SELECT complaints.*, 
               users.name, 
               users.department
        FROM complaints
        JOIN users ON complaints.user_id = users.id
        WHERE 1=1";

if (!empty($search)) {

    $sql .= " AND (
                users.name LIKE '%$search%'
                OR users.department LIKE '%$search%'
                OR complaints.title LIKE '%$search%'
                OR complaints.category LIKE '%$search%'
                OR complaints.description LIKE '%$search%'
              )";
}

if (!empty($status_filter)) {
    $sql .= " AND complaints.status = '$status_filter'";
}

$sql .= " ORDER BY complaints.created_at DESC";

$result = mysqli_query($conn, $sql);


/* ================= STATISTICS ================= */

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints");
$total = mysqli_fetch_assoc($total_result)['total'];

$pending_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Pending'");
$pending = mysqli_fetch_assoc($pending_result)['total'];

$progress_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='In Progress'");
$progress = mysqli_fetch_assoc($progress_result)['total'];

$resolved_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Resolved'");
$resolved = mysqli_fetch_assoc($resolved_result)['total'];

$rejected_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Rejected'");
$rejected = mysqli_fetch_assoc($rejected_result)['total'];


/* ================= RECENT COMPLAINTS ================= */

$recent_sql = "SELECT complaints.*, users.name
               FROM complaints
               JOIN users ON complaints.user_id = users.id
               ORDER BY complaints.created_at DESC
               LIMIT 5";

$recent_result = mysqli_query($conn, $recent_sql);

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard - Office Complaint System</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #F1F5FF;
    color: #1E293B;
}


/* ================= HEADER ================= */

.header {
    background: linear-gradient(135deg, #1E3A8A, #2563EB);
    color: white;
    padding: 22px 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    margin: 0;
    font-size: 25px;
}

.header p {
    margin: 5px 0 0;
    opacity: 0.9;
}

.logout {
    background: white;
    color: #1E3A8A;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: bold;
}


/* ================= MAIN ================= */

.container {
    width: 94%;
    max-width: 1400px;
    margin: 30px auto;
}


/* ================= STAT CARDS ================= */

.stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 5px 18px rgba(30,58,138,0.08);
    border-left: 5px solid #2563EB;
}

.stat-card h3 {
    margin: 0;
    font-size: 15px;
    color: #64748B;
}

.stat-card .number {
    font-size: 30px;
    font-weight: bold;
    margin-top: 10px;
    color: #1E3A8A;
}

.pending-card {
    border-left-color: #F59E0B;
}

.progress-card {
    border-left-color: #6366F1;
}

.resolved-card {
    border-left-color: #16A34A;
}

.rejected-card {
    border-left-color: #DC2626;
}


/* ================= OVERVIEW ================= */

.overview {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 22px;
    margin-bottom: 30px;
}

.panel {
    background: white;
    padding: 24px;
    border-radius: 14px;
    box-shadow: 0 5px 18px rgba(30,58,138,0.08);
}

.panel h2 {
    margin-top: 0;
    color: #1E3A8A;
    font-size: 20px;
}


/* ================= CHART ================= */

.chart-row {
    margin: 17px 0;
}

.chart-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 7px;
    font-size: 14px;
    font-weight: bold;
}

.chart-bg {
    height: 13px;
    background: #E2E8F0;
    border-radius: 20px;
    overflow: hidden;
}

.chart-bar {
    height: 100%;
    border-radius: 20px;
}

.bar-pending {
    background: #F59E0B;
}

.bar-progress {
    background: #6366F1;
}

.bar-resolved {
    background: #16A34A;
}

.bar-rejected {
    background: #DC2626;
}


/* ================= RECENT ================= */

.recent-table {
    width: 100%;
    border-collapse: collapse;
}

.recent-table th,
.recent-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #E2E8F0;
    text-align: left;
    font-size: 14px;
}

.recent-table th {
    color: #64748B;
    font-size: 13px;
}

.status {
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
}

.status-Pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-In-Progress {
    background: #E0E7FF;
    color: #3730A3;
}

.status-Resolved {
    background: #DCFCE7;
    color: #166534;
}

.status-Rejected {
    background: #FEE2E2;
    color: #991B1B;
}


/* ================= SEARCH ================= */

.search-box {
    background: white;
    padding: 20px;
    border-radius: 14px;
    margin-bottom: 22px;
    box-shadow: 0 5px 18px rgba(30,58,138,0.08);
}

.search-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-form input,
.search-form select {
    padding: 11px;
    border: 1px solid #CBD5E1;
    border-radius: 7px;
    outline: none;
}

.search-form input {
    flex: 1;
    min-width: 220px;
}

.search-btn {
    background: #2563EB;
    color: white;
    border: none;
    padding: 11px 20px;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
}

.search-btn:hover {
    background: #1D4ED8;
}

.reset-btn {
    background: #E2E8F0;
    color: #1E293B;
    text-decoration: none;
    padding: 11px 18px;
    border-radius: 7px;
    font-weight: bold;
}


/* ================= ACTION BAR ================= */

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    gap: 10px;
}

.manage-btn {
    background: #2563EB;
    color: white;
    text-decoration: none;
    padding: 11px 18px;
    border-radius: 8px;
    font-weight: bold;
}

.delete-btn {
    background: #DC2626;
    color: white;
    border: none;
    padding: 11px 18px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

.delete-btn:disabled {
    background: #CBD5E1;
    cursor: not-allowed;
}

.selected-count {
    color: #64748B;
    font-size: 14px;
    margin-left: 8px;
}


/* ================= TABLE ================= */

.table-container {
    background: white;
    border-radius: 14px;
    box-shadow: 0 5px 18px rgba(30,58,138,0.08);
    overflow-x: auto;
}

.complaint-table {
    width: 100%;
    min-width: 1100px;
    border-collapse: collapse;
}

.complaint-table th {
    background: #EFF6FF;
    color: #1E3A8A;
    padding: 14px 10px;
    text-align: left;
    font-size: 13px;
}

.complaint-table td {
    padding: 14px 10px;
    border-bottom: 1px solid #E2E8F0;
    font-size: 13px;
}

.complaint-table tr:hover {
    background: #F8FAFC;
}

.update-btn {
    background: #2563EB;
    color: white;
    text-decoration: none;
    padding: 7px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
}


/* ================= MESSAGES ================= */

.message {
    padding: 13px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: bold;
}

.success {
    background: #DCFCE7;
    color: #166534;
}

.error {
    background: #FEE2E2;
    color: #991B1B;
}

.warning {
    background: #FEF3C7;
    color: #92400E;
}


/* ================= RESPONSIVE ================= */

@media (max-width: 1000px) {

    .stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .overview {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 600px) {

    .header {
        padding: 18px;
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .container {
        width: 96%;
    }

    .stats {
        grid-template-columns: 1fr;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .manage-btn,
    .delete-btn {
        text-align: center;
    }
}

</style>

</head>

<body>


<!-- ================= HEADER ================= -->

<div class="header">

    <div>
        <h1>Office Complaint System</h1>
        <p>Administrator Dashboard</p>
    </div>

    <a href="logout.php" class="logout">Logout</a>

</div>


<div class="container">


<!-- ================= MESSAGES ================= -->

<?php

if (isset($_GET['message'])) {

    if ($_GET['message'] == 'deleted') {
        echo '<div class="message success">Selected complaints deleted successfully.</div>';
    }

    if ($_GET['message'] == 'error') {
        echo '<div class="message error">Something went wrong while deleting complaints.</div>';
    }

    if ($_GET['message'] == 'no_selection') {
        echo '<div class="message warning">Please select at least one complaint.</div>';
    }
}

?>


<!-- ================= STATISTICS ================= -->

<div class="stats">

    <div class="stat-card">
        <h3>Total Complaints</h3>
        <div class="number"><?php echo $total; ?></div>
    </div>

    <div class="stat-card pending-card">
        <h3>Pending</h3>
        <div class="number"><?php echo $pending; ?></div>
    </div>

    <div class="stat-card progress-card">
        <h3>In Progress</h3>
        <div class="number"><?php echo $progress; ?></div>
    </div>

    <div class="stat-card resolved-card">
        <h3>Resolved</h3>
        <div class="number"><?php echo $resolved; ?></div>
    </div>

    <div class="stat-card rejected-card">
        <h3>Rejected</h3>
        <div class="number"><?php echo $rejected; ?></div>
    </div>

</div>


<!-- ================= OVERVIEW ================= -->

<div class="overview">


    <!-- STATUS OVERVIEW -->

    <div class="panel">

        <h2>Complaint Status Overview</h2>

        <?php

        $max_value = max($pending, $progress, $resolved, $rejected, 1);

        ?>

        <div class="chart-row">

            <div class="chart-label">
                <span>Pending</span>
                <span><?php echo $pending; ?></span>
            </div>

            <div class="chart-bg">
                <div class="chart-bar bar-pending"
                     style="width: <?php echo ($pending / $max_value) * 100; ?>%;">
                </div>
            </div>

        </div>


        <div class="chart-row">

            <div class="chart-label">
                <span>In Progress</span>
                <span><?php echo $progress; ?></span>
            </div>

            <div class="chart-bg">
                <div class="chart-bar bar-progress"
                     style="width: <?php echo ($progress / $max_value) * 100; ?>%;">
                </div>
            </div>

        </div>


        <div class="chart-row">

            <div class="chart-label">
                <span>Resolved</span>
                <span><?php echo $resolved; ?></span>
            </div>

            <div class="chart-bg">
                <div class="chart-bar bar-resolved"
                     style="width: <?php echo ($resolved / $max_value) * 100; ?>%;">
                </div>
            </div>

        </div>


        <div class="chart-row">

            <div class="chart-label">
                <span>Rejected</span>
                <span><?php echo $rejected; ?></span>
            </div>

            <div class="chart-bg">
                <div class="chart-bar bar-rejected"
                     style="width: <?php echo ($rejected / $max_value) * 100; ?>%;">
                </div>
            </div>

        </div>

    </div>


    <!-- RECENT COMPLAINTS -->

    <div class="panel">

        <h2>Recent Complaints</h2>

        <table class="recent-table">

            <tr>
                <th>Employee</th>
                <th>Complaint</th>
                <th>Status</th>
            </tr>

            <?php

            if (mysqli_num_rows($recent_result) > 0) {

                while ($recent = mysqli_fetch_assoc($recent_result)) {

                    $status_class = str_replace(" ", "-", $recent['status']);

                    echo "<tr>";

                    echo "<td>" . htmlspecialchars($recent['name']) . "</td>";

                    echo "<td>" . htmlspecialchars($recent['title']) . "</td>";

                    echo "<td>
                            <span class='status status-$status_class'>
                            " . htmlspecialchars($recent['status']) . "
                            </span>
                          </td>";

                    echo "</tr>";
                }

            } else {

                echo "<tr>
                        <td colspan='3'>No complaints available.</td>
                      </tr>";
            }

            ?>

        </table>

    </div>

</div>


<!-- ================= SEARCH ================= -->

<div class="search-box">

    <form method="GET" class="search-form">

        <input
            type="text"
            name="search"
            placeholder="Search employee, department, title, category..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <select name="status">

            <option value="">All Status</option>

            <option value="Pending"
                <?php if ($status_filter == "Pending") echo "selected"; ?>>
                Pending
            </option>

            <option value="In Progress"
                <?php if ($status_filter == "In Progress") echo "selected"; ?>>
                In Progress
            </option>

            <option value="Resolved"
                <?php if ($status_filter == "Resolved") echo "selected"; ?>>
                Resolved
            </option>

            <option value="Rejected"
                <?php if ($status_filter == "Rejected") echo "selected"; ?>>
                Rejected
            </option>

        </select>

        <button type="submit" class="search-btn">
            Search
        </button>

        <a href="admin.php" class="reset-btn">
            Reset
        </a>

    </form>

</div>


<!-- ================= ACTION BAR ================= -->

<div class="action-bar">

    <div>

        <form method="POST"
              id="deleteForm"
              onsubmit="return confirmDelete();">

            <button
                type="submit"
                name="delete_selected"
                class="delete-btn"
                id="deleteBtn"
                disabled
            >
                🗑 Delete Selected
            </button>

            <span class="selected-count" id="selectedCount">
                0 selected
            </span>

    </div>

    <a href="users.php" class="manage-btn">
        Manage Employees
    </a>

</div>


<!-- ================= COMPLAINT TABLE ================= -->

<div class="table-container">

<table class="complaint-table">

<thead>

<tr>

    <th>
        <input type="checkbox" id="selectAll">
    </th>

    <th>ID</th>
    <th>Employee</th>
    <th>Department</th>
    <th>Title</th>
    <th>Category</th>
    <th>Priority</th>
    <th>Status</th>
    <th>Date</th>
    <th>Action</th>

</tr>

</thead>

<tbody>

<?php

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $status_class = str_replace(" ", "-", $row['status']);

        $priority_class = strtolower($row['priority']);

?>

<tr>

    <td>
        <input
            type="checkbox"
            name="selected_complaints[]"
            value="<?php echo $row['id']; ?>"
            class="complaint-checkbox"
        >
    </td>

    <td>
        <?php echo $row['id']; ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['name']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['department']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['title']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['category']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['priority']); ?>
    </td>

    <td>

        <span class="status status-<?php echo $status_class; ?>">
            <?php echo htmlspecialchars($row['status']); ?>
        </span>

    </td>

    <td>
        <?php echo date("d M Y", strtotime($row['created_at'])); ?>
    </td>

    <td>

        <a
            href="update_complaint.php?id=<?php echo $row['id']; ?>"
            class="update-btn"
        >
            Update
        </a>

    </td>

</tr>

<?php

    }

} else {

?>

<tr>

    <td colspan="10" style="text-align:center; padding:30px;">
        No complaints found.
    </td>

</tr>

<?php

}

?>

</tbody>

</table>

</form>

</div>

</div>


<!-- ================= JAVASCRIPT ================= -->

<script>

const selectAll = document.getElementById("selectAll");
const checkboxes = document.querySelectorAll(".complaint-checkbox");
const deleteBtn = document.getElementById("deleteBtn");
const selectedCount = document.getElementById("selectedCount");


function updateSelection() {

    let selected = document.querySelectorAll(
        ".complaint-checkbox:checked"
    ).length;

    selectedCount.innerText = selected + " selected";

    deleteBtn.disabled = selected === 0;

    if (selected === checkboxes.length && checkboxes.length > 0) {
        selectAll.checked = true;
    } else {
        selectAll.checked = false;
    }
}


selectAll.addEventListener("change", function() {

    checkboxes.forEach(function(checkbox) {

        checkbox.checked = selectAll.checked;

    });

    updateSelection();

});


checkboxes.forEach(function(checkbox) {

    checkbox.addEventListener("change", function() {

        updateSelection();

    });

});


function confirmDelete() {

    let selected = document.querySelectorAll(
        ".complaint-checkbox:checked"
    ).length;

    if (selected === 0) {

        alert("Please select at least one complaint.");

        return false;
    }

    return confirm(
        "Are you sure you want to delete " +
        selected +
        " selected complaint(s)?\n\nThis action cannot be undone."
    );
}

</script>


</body>
</html>

