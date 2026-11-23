<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$query = "
SELECT
    ch.name AS child_name,
    v.vaccine_name,
    vr.next_due_date,
    vc.center_name,
    vr.status
FROM vaccination_records vr
JOIN children ch ON vr.child_id = ch.child_id
JOIN vaccines v ON vr.vaccine_id = v.vaccine_id
JOIN vaccination_centers vc ON vr.center_id = vc.center_id
WHERE vr.status = 'pending'
ORDER BY vr.next_due_date ASC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reminders and Notifications</title>

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f0f5ff;
}

.header {
    background: #1769aa;
    color: white;
    padding: 22px;
    text-align: center;
}

.container {
    max-width: 950px;
    margin: 30px auto;
    padding: 20px;
}

.card {
    background: white;
    padding: 22px;
    margin-bottom: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px #ddd;
}

h2 {
    color: #1769aa;
}

.status {
    color: #d97706;
    font-weight: bold;
}

.back {
    display: inline-block;
    margin-bottom: 20px;
    color: #1769aa;
    text-decoration: none;
    font-weight: bold;
}

</style>
</head>

<body>

<div class="header">
    <h1>Child Vaccination Monitoring System</h1>
    <p>Reminders and Notifications</p>
</div>

<div class="container">

<a href="dashboard.php" class="back">
    Back to Dashboard
</a>

<h2>Upcoming Vaccination Reminders</h2>

<?php if (mysqli_num_rows($result) > 0) { ?>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <div class="card">

            <h3>
                <?php echo htmlspecialchars($row['child_name']); ?>
            </h3>

            <p>
                <strong>Vaccine:</strong>
                <?php echo htmlspecialchars($row['vaccine_name']); ?>
            </p>

            <p>
                <strong>Due Date:</strong>
                <?php echo htmlspecialchars(
                    $row['next_due_date'] ?? 'Not scheduled'
                ); ?>
            </p>

            <p>
                <strong>Centre:</strong>
                <?php echo htmlspecialchars($row['center_name']); ?>
            </p>

            <p class="status">
                Status: Pending
            </p>

        </div>

    <?php } ?>

<?php } else { ?>

    <div class="card">
        No pending vaccination reminders found.
    </div>

<?php } ?>

</div>

</body>
</html>