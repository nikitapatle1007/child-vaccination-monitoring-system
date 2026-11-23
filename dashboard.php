<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

// Total children
$totalQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM children");
$totalChildren = mysqli_fetch_assoc($totalQuery)["total"];

// Completed vaccinations
$completedQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM vaccination_records
     WHERE status = 'completed'");
$completedVaccinations = mysqli_fetch_assoc($completedQuery)["total"];

// Upcoming vaccinations (pending records)
$pendingQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM vaccination_records
     WHERE status = 'pending'");
$upcomingVaccinations = mysqli_fetch_assoc($pendingQuery)["total"];

// Recent activity
$recentQuery = mysqli_query($conn,
    "SELECT
        ch.name AS child_name,
        v.vaccine_name,
        vr.vaccination_date,
        vr.next_due_date,
        vr.status
     FROM vaccination_records vr
     JOIN children ch ON vr.child_id = ch.child_id
     JOIN vaccines v ON vr.vaccine_id = v.vaccine_id
     ORDER BY vr.record_id DESC
     LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard - Child Vaccination Monitoring System</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f2f6fc;
}

.sidebar {
    width: 230px;
    height: 100vh;
    background: #1769aa;
    color: white;
    position: fixed;
    padding: 20px;
}

.sidebar h2 {
    font-size: 20px;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 13px;
    margin: 8px 0;
    border-radius: 5px;
}

.sidebar a:hover {
    background: #0d4f85;
}

.main {
    margin-left: 230px;
    padding: 25px;
}

.header {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 8px #ddd;
}

.card h3 {
    color: #555;
    font-size: 16px;
}

.card h1 {
    color: #1769aa;
    font-size: 32px;
}

.activity {
    background: white;
    padding: 20px;
    border-radius: 8px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

th, td {
    padding: 13px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background: #eaf3ff;
    color: #1769aa;
}

.status {
    font-weight: bold;
}

@media (max-width: 750px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .main {
        margin-left: 0;
    }

    .cards {
        grid-template-columns: 1fr;
    }
}

</style>
</head>

<body>

<div class="sidebar">

    <h2>Child Vaccination System</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="register.html">Register Child</a>

    <a href="schedule.php">Vaccine Schedule</a>

    <a href="records.php">Vaccination Records</a>

    <a href="reminder.php">Reminders</a>

    <a href="logout.php">Logout</a>

</div>

<div class="main">

    <div class="header">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!</h2>
        <p>Child Vaccination Monitoring System Dashboard</p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Total Children</h3>
            <h1><?php echo $totalChildren; ?></h1>
        </div>

        <div class="card">
            <h3>Vaccination Completed</h3>
            <h1><?php echo $completedVaccinations; ?></h1>
        </div>

        <div class="card">
            <h3>Upcoming Vaccination</h3>
            <h1><?php echo $upcomingVaccinations; ?></h1>
        </div>

    </div>

    <div class="activity">

        <h2>Recent Activity</h2>

        <table>

            <tr>
                <th>Child Name</th>
                <th>Vaccine</th>
                <th>Vaccination Date</th>
                <th>Next Due Date</th>
                <th>Status</th>
            </tr>

            <?php if (mysqli_num_rows($recentQuery) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($recentQuery)) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row["child_name"]); ?></td>

                        <td><?php echo htmlspecialchars($row["vaccine_name"]); ?></td>

                        <td>
                            <?php
                            echo !empty($row["vaccination_date"])
                                ? htmlspecialchars($row["vaccination_date"])
                                : "Not scheduled";
                            ?>
                        </td>

                        <td>
                            <?php
                            echo !empty($row["next_due_date"])
                                ? htmlspecialchars($row["next_due_date"])
                                : "Not scheduled";
                            ?>
                        </td>

                        <td class="status">
                            <?php echo htmlspecialchars($row["status"]); ?>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="5">No vaccination records found.</td>
                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>