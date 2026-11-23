<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "db.php";

$query = "
SELECT vaccine_id, vaccine_name, description, age_group
FROM vaccines
ORDER BY vaccine_id ASC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Schedule</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f0f5ff;
        }

        .header {
            background: #1769aa;
            color: white;
            padding: 22px;
            text-align: center;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        .table-box {
            background: white;
            padding: 22px;
            border-radius: 10px;
            overflow-x: auto;
        }

        h2 {
            color: #1769aa;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #e5f1ff;
            color: #1769aa;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #1769aa;
            text-decoration: none;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Child Vaccination Monitoring System</h1>
    <p>Vaccination Schedule</p>
</div>

<div class="container">

    <a href="dashboard.php" class="back">
        Back to Dashboard
    </a>

    <div class="table-box">

        <h2>Available Vaccines</h2>

        <table>

            <tr>
                <th>Vaccine ID</th>
                <th>Vaccine Name</th>
                <th>Description</th>
                <th>Age Group</th>
            </tr>

            <?php if (mysqli_num_rows($result) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?php echo $row['vaccine_id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row['vaccine_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['description']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['age_group']); ?>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="4" class="empty">
                        No vaccine records available.
                    </td>
                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>