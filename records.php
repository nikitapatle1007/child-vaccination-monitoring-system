<?php
include "db.php";

// Fetch children
$children = mysqli_query($conn,
    "SELECT child_id, name FROM children");

// Fetch vaccines
$vaccines = mysqli_query($conn,
    "SELECT vaccine_id, vaccine_name FROM vaccines");

// Fetch vaccination centres
$centres = mysqli_query($conn,
    "SELECT center_id, center_name FROM vaccination_centers");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $child_id = $_POST["child_id"];
    $vaccine_id = $_POST["vaccine_id"];
    $center_id = $_POST["center_id"];
    $vaccination_date = $_POST["vaccination_date"];
    $next_due_date = $_POST["next_due_date"];
    $status = $_POST["status"];

    $stmt = mysqli_prepare($conn,
        "INSERT INTO vaccination_records
        (child_id, vaccine_id, center_id,
         vaccination_date, next_due_date, status)
        VALUES (?, ?, ?, NULLIF(?, ''), NULLIF(?, ''), ?)");

    mysqli_stmt_bind_param(
        $stmt, "iiisss",
        $child_id,
        $vaccine_id,
        $center_id,
        $vaccination_date,
        $next_due_date,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = "Vaccination record saved successfully!";
    } else {
        $message = "Unable to save vaccination record.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vaccination Records</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f5ff;
            padding: 30px;
        }

        .container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #1769aa;
        }

        label {
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            box-sizing: border-box;
        }

        button {
            background: #1769aa;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            color: green;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Add Vaccination Record</h2>

    <p class="message">
        <?php echo htmlspecialchars($message); ?>
    </p>

    <form method="POST">

        <label>Select Child</label>
        <select name="child_id" required>
            <option value="">Select Child</option>
            <?php while ($row = mysqli_fetch_assoc($children)) { ?>
                <option value="<?php echo $row['child_id']; ?>">
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Select Vaccine</label>
        <select name="vaccine_id" required>
            <option value="">Select Vaccine</option>
            <?php while ($row = mysqli_fetch_assoc($vaccines)) { ?>
                <option value="<?php echo $row['vaccine_id']; ?>">
                    <?php echo htmlspecialchars($row['vaccine_name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Select Vaccination Centre</label>
        <select name="center_id" required>
            <option value="">Select Centre</option>
            <?php while ($row = mysqli_fetch_assoc($centres)) { ?>
                <option value="<?php echo $row['center_id']; ?>">
                    <?php echo htmlspecialchars($row['center_name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Vaccination Date</label>
        <input type="date" name="vaccination_date">

        <label>Next Due Date</label>
        <input type="date" name="next_due_date">

        <label>Status</label>
        <select name="status" required>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
        </select>

        <button type="submit">Save Record</button>

    </form>
<hr>

<h2>All Vaccination Records</h2>

<table border="1" cellpadding="10" cellspacing="0"
       width="100%">

<tr>
    <th>Child ID</th>
    <th>Child Name</th>
    <th>Vaccine</th>
    <th>Centre</th>
    <th>Vaccination Date</th>
    <th>Next Due Date</th>
    <th>Status</th>
</tr>

<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include"db.php";
$query = "
SELECT
    vr.child_id,
    ch.name AS child_name,
    v.vaccine_name,
    vc.center_name,
    vr.vaccination_date,
    vr.next_due_date,
    vr.status
FROM vaccination_records vr
JOIN children ch ON vr.child_id = ch.child_id
JOIN vaccines v ON vr.vaccine_id = v.vaccine_id
JOIN vaccination_centers vc ON vr.center_id = vc.center_id
";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
?>

<tr>
    <td><?php echo $row['child_id']; ?></td>
    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
    <td><?php echo htmlspecialchars($row['center_name']); ?></td>
    <td><?php echo $row['vaccination_date'] ?? 'Not entered'; ?></td>
    <td><?php echo $row['next_due_date'] ?? 'Not entered'; ?></td>
    <td><?php echo htmlspecialchars($row['status']); ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>